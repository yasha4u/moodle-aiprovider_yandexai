<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   aiprovider_yandexai
 * @copyright 2024 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_yandexai;

defined('MOODLE_INTERNAL') || die();

use core_ai\ai_image;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class process image generation.
 *
 * @package   aiprovider_yandexai
 * @copyright 2024 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_generate_image extends abstract_processor {
    /**
     * @return string
     * @throws \dml_exception
     */
    protected function get_imageurl(): string {
        return $this->get_setting('getimageurl');
    }

    /**
     * @return array
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \moodle_exception
     * @throws \stored_file_creation_exception
     */
    #[\Override]
    protected function query_ai_api(): array {
        $response = parent::query_ai_api();

        if ($response['success']) {
            // Getting the image and saving it to a file.
            $fileobj = $this->save_to_file(
                $this->action->get_configuration('userid'),
                $response['id']
            );
            // Add the file to the response, so the calling placement can do whatever they want with it.
            $response['draftfile'] = $fileobj;
        }

        return $response;
    }

    /**
     * Getting the image aspect ratio
     *
     * @param string $ratio
     * @return string[]
     * @throws \coding_exception
     */
    private function get_size(string $ratio): array {
        if ($ratio === 'square') {
            $width = '1';
            $height = '1';
        } else if ($ratio === 'landscape') {
            $width = '2';
            $height = '1';
        } else if ($ratio === 'portrait') {
            $width = '1';
            $height = '2';
        } else {
            throw new \coding_exception('Invalid aspect ratio: ' . $ratio);
        }
        return [$width, $height];
    }

    /**
     * @param string $userid
     * @return RequestInterface
     * @throws \coding_exception
     * @throws \dml_exception
     */
    #[\Override]
    protected function create_request_object(string $userid): RequestInterface {
        // Create the message object.
        $message = new \stdClass();
        $message->weight = 1;
        $message->text = $this->action->get_configuration('prompttext');

        // Create the completion options object.
        $generationoptions = new \stdClass();
        $generationoptions->seed = rand(1, 9999999);
        [$width, $height] = $this->get_size($this->action->get_configuration('aspectratio'));

        $generationoptions->aspectRatio = [
            'widthRatio' => $width,
            'heightRatio' => $height
        ];

        // Create the request object.
        $requestobj = new \stdClass();
        $requestobj->modelUri = $this->get_model();
        $requestobj->generationOptions = $generationoptions;
        $requestobj->messages = [$message];

        return new Request(
            method: 'POST',
            uri: '',
            headers: [
                'Content-Type' => 'application/json',
            ],
            body: json_encode($requestobj),
        );
    }

    /**
     * @param ResponseInterface $response
     * @return array
     */
    #[\Override]
    protected function handle_api_success(ResponseInterface $response): array {
        $responsebody = $response->getBody();
        $bodyobj = json_decode($responsebody->getContents());

        return [
            'success' => true,
            'id' => $bodyobj->id,
            'description' => $bodyobj->description ?? '',
            'createdAt' => $bodyobj->createdAt ?? '',
            'createdBy' => $bodyobj->createdBy ?? '',
            'modifiedAt' => $bodyobj->modifiedAt ?? '',
            'done' => $bodyobj->done ?? '',
            'metadata' => $bodyobj->metadata ?? ''
        ];
    }

    /**
     * Retrieving and saving the image to a file.
     *
     * @param int $userid
     * @param string $imageid
     * @return \stored_file
     * @throws \coding_exception
     * @throws \core\exception\moodle_exception
     * @throws \dml_exception
     * @throws \file_exception
     * @throws \moodle_exception
     * @throws \stored_file_creation_exception
     */
    private function save_to_file(int $userid, string $imageid): \stored_file {
        global $CFG;

        require_once("{$CFG->libdir}/filelib.php");

        $curl = new \curl();
        $curl->setopt(['CURLOPT_TIMEOUT' => 30, 'CURLOPT_CONNECTTIMEOUT' => 5]);
        $curl->setHeader(
            [
                "Authorization: Api-Key " . $this->provider->config['apikey'],
                "Content-Type: application/json"
            ]
        );

        do {
            // Pause before retrieving the image
            // https://yandex.cloud/ru/docs/foundation-models/quickstart/yandexart#api_1
            sleep(5);
            // Getting the image
            $getdata = $curl->get($this->get_imageurl() . $imageid);
            $decoded_data = json_decode($getdata);
        } while ($decoded_data->done == false);

        $filename = 'image_' . uniqid() . '.jpeg'; // Get the basename of the path.
        // Download the image and add the watermark.
        $tempdst = make_request_directory() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($tempdst, base64_decode($decoded_data->response->image));

        $image = new ai_image($tempdst);
        $image->add_watermark()->save();

        // We put the file in the user draft area initially.
        // Placements (on behalf of the user) can then move it to the correct location.
        $fileinfo = new \stdClass();
        $fileinfo->contextid = \context_user::instance($userid)->id;
        $fileinfo->filearea = 'draft';
        $fileinfo->component = 'user';
        $fileinfo->itemid = file_get_unused_draft_itemid();
        $fileinfo->filepath = '/';
        $fileinfo->filename = $filename;

        $fs = get_file_storage();
        return $fs->create_file_from_string($fileinfo, file_get_contents($tempdst));
    }
}
