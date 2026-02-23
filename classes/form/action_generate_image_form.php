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

namespace aiprovider_yandexai\form;

defined('MOODLE_INTERNAL') || die();

use core_ai\form\action_settings_form;

/**
 * The configuration form class for image generation.
 */
class action_generate_image_form extends action_settings_form {
    /**
     * @var string Plugin name.
     */
    protected const PLUGINNAME = 'aiprovider_yandexai';

    /**
     * @return void
     * @throws \coding_exception
     */
    #[\Override]
    protected function definition() {
        $mform = $this->_form;
        $actionconfig = $this->_customdata['actionconfig']['settings'] ?? [];
        $returnurl = $this->_customdata['returnurl'] ?? null;
        $actionname = $this->_customdata['actionname'];
        $action = $this->_customdata['action'];
        $providerid = $this->_customdata['providerid'] ?? 0;

        // Action type
        $mform->addElement('hidden', 'action', $action);
        $mform->setType('action', PARAM_TEXT);

        // Provider name
        $mform->addElement('hidden', 'provider', self::PLUGINNAME);
        $mform->setType('provider', PARAM_TEXT);

        // Provider ID
        $mform->addElement('hidden', 'providerid', $providerid);
        $mform->setType('providerid', PARAM_INT);

        if ($returnurl) {
            $mform->addElement('hidden', 'returnurl', $returnurl);
            $mform->setType('returnurl', PARAM_LOCALURL);
        }

        // AI model endpoint
        $mform->addElement(
            'text',
            'model',
            get_string("action:{$actionname}:model", self::PLUGINNAME),
            'maxlength="255" size="30"',
        );
        $mform->setType('model', PARAM_TEXT);
        $mform->setDefault('model', $actionconfig['model']
            ?? 'art://catalogue_id/yandex-art/latest');
        $mform->addHelpButton('model', "action:{$actionname}:model", self::PLUGINNAME);
        $mform->addRule('model', null, 'required', null, 'client');

        // API URL
        $mform->addElement(
            'text',
            'endpoint',
            get_string("endpoint", self::PLUGINNAME),
            'maxlength="255" size="30"',
        );
        $mform->setType('endpoint', PARAM_URL);
        $mform->setDefault('endpoint', $actionconfig['endpoint']
            ?? 'https://llm.api.cloud.yandex.net/foundationModels/v1/imageGenerationAsync');
        $mform->addRule('endpoint', null, 'required', null, 'client');

        // Generation result endpoint
        $mform->addElement(
            'text',
            'getimageurl',
            get_string("action:{$actionname}:getimageurl", self::PLUGINNAME),
            'maxlength="255" size="30"',
        );
        $mform->setType('getimageurl', PARAM_URL);
        $mform->setDefault('getimageurl', $actionconfig['getimageurl']
            ?? 'https://llm.api.cloud.yandex.net:443/operations/');
        $mform->addRule('getimageurl', null, 'required', null, 'client');

        $this->set_data($actionconfig);
    }
}
