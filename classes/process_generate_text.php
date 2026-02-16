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

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Класс генерации текста
 *
 * @package   aiprovider_yandexai
 * @copyright 2024 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_generate_text extends abstract_processor {
    /**
     * @return string
     * @throws \dml_exception
     */
    protected function allowhtml(): string {
        return $this->get_setting('allowhtml');
    }

    /**
     * @param string $userid
     * @return RequestInterface
     * @throws \dml_exception
     */
    #[\Override]
    protected function create_request_object(string $userid): RequestInterface {
        // Create the message object.
        $message = new \stdClass();
        $message->role = 'user';
        $message->text = $this->action->get_configuration('prompttext');

        // Create the completion options object.
        $completionoptions = new \stdClass();
        $completionoptions->stream = false;
        $completionoptions->temperature = $this->get_temperature();
        $completionoptions->maxTokens = 2000;

        // Create the request object.
        $requestobj = new \stdClass();
        $requestobj->modelUri = $this->get_model();
        $requestobj->completionOptions = $completionoptions;

        $systeminstruction = $this->get_system_instruction();

        if (!empty($systeminstruction)) {
            $systemmessage = new \stdClass();
            $systemmessage->role = 'system';
            $systemmessage->text = $systeminstruction;
            $requestobj->messages = [$systemmessage, $message];
        } else {
            $requestobj->messages = [$message];
        }

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
     * Handle a successful response from the external AI api.
     *
     * @param ResponseInterface $response The response object.
     * @return array The response.
     */
    protected function handle_api_success(ResponseInterface $response): array {
        $responsebody = $response->getBody();
        $bodyobj = json_decode($responsebody->getContents());
        $content = $bodyobj->result->alternatives[0]->message->text;

        // Если установлена опция "Использовать html ответы", то конвертируем markdown в html.
        if ($this->allowhtml()) {
            $content = markdown_to_html($content);
        }

        return [
            'success' => true,
            'id' => $bodyobj->id ?? 0,
            'generatedcontent' => $content,
            'prompttokens' => $bodyobj->result->usage->inputTextTokens,
            'completiontokens' => $bodyobj->result->usage->completionTokens,
        ];
    }
}
