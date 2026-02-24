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
 * Base text generation configuration form.
 *
 * @package   aiprovider_yandexai
 * @copyright 2025 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_yandexai\form;

use core_ai\form\action_settings_form;

/**
 * Base text generation configuration form class.
 * Common form elements are located here.
 */
class base_form extends action_settings_form {
    /**
     * @var string Plugin name.
     */
    protected const PLUGINNAME = 'aiprovider_yandexai';

    /**
     * @var array Action configuration.
     */
    protected array $actionconfig;

    /**
     * @var string|null Return URL.
     */
    protected ?string $returnurl;

    /**
     * @var string Action name.
     */
    protected string $actionname;

    /**
     * @var string Action class.
     */
    protected string $action;

    /**
     * @var int Provider ID.
     */
    protected int $providerid;

    /**
     * @var string Provider name.
     */
    protected string $providername;

    /**
     * @var string Is html allowed?
     */
    protected string $allowhtml;

    /**
     * Form definition.
     *
     * @return void
     * @throws \coding_exception
     */
    #[\Override]
    protected function definition() {
        $mform = $this->_form;
        $this->actionconfig = $this->_customdata['actionconfig']['settings'] ?? [];
        $this->returnurl = $this->_customdata['returnurl'] ?? null;
        $this->actionname = $this->_customdata['actionname'];
        $this->action = $this->_customdata['action'];
        $this->providerid = $this->_customdata['providerid'] ?? 0;
        $this->providername = $this->_customdata['providername'] ?? self::PLUGINNAME;
        $this->allowhtml = $this->_customdata['allowhtml'] ?? false;

        // Action type.
        $mform->addElement('hidden', 'action', $this->action);
        $mform->setType('action', PARAM_TEXT);

        // Provider name.
        $mform->addElement('hidden', 'provider', self::PLUGINNAME);
        $mform->setType('provider', PARAM_TEXT);

        // Provider ID.
        $mform->addElement('hidden', 'providerid', $this->providerid);
        $mform->setType('providerid', PARAM_INT);

        $fields = [
            // AI model endpoint.
            [
                'element' => 'text', 'name' => 'model', 'type' => PARAM_TEXT,
                'help' => true, 'default' => 'gpt://catalogue_id/yandexgpt',
            ],
            // API URL.
            [
                'element' => 'text', 'name' => 'endpoint', 'type' => PARAM_URL, 'help' => false,
                'default' => 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion',
            ],
            // Generation temperature.
            [
                'element' => 'text', 'name' => 'temperature', 'type' => PARAM_FLOAT,
                'help' => true, 'default' => '0.25',
            ],
        ];

        foreach ($fields as $field) {
            // Adding a field to the form.
            self::add_form_field($field);
        }

        // System instruction.
        $mform->addElement(
            'textarea',
            'systeminstruction',
            get_string("action:systeminstruction", self::PLUGINNAME),
            'wrap="virtual" rows="5" cols="20"',
        );
        $mform->setType('systeminstruction', PARAM_TEXT);
        $mform->setDefault('systeminstruction', empty($this->actionconfig['systeminstruction'])
            ? $this->action::get_system_instruction() : $this->actionconfig['systeminstruction']);
        $mform->addHelpButton('systeminstruction', "action:systeminstruction", self::PLUGINNAME);
        $mform->addRule('systeminstruction', null, 'required', null, 'client');

        if ($this->returnurl) {
            $mform->addElement('hidden', 'returnurl', $this->returnurl);
            $mform->setType('returnurl', PARAM_LOCALURL);
        }
    }

    /**
     * Method adding fields of type 'passwordunmask' and 'text'.
     *
     * @param $field
     * @return void
     * @throws \coding_exception
     */
    protected function add_form_field($field): void {
        $mform = $this->_form;

        // Checking for the presence of a string in the language file.
        $strexists = get_string_manager()->string_exists("action:{$this->actionname}:{$field['name']}", self::PLUGINNAME);
        $langstr = $strexists
            ? get_string("action:{$this->actionname}:{$field['name']}", self::PLUGINNAME)
            : get_string($field['name'], self::PLUGINNAME);

        // Adding a field to the form.
        $mform->addElement(
            $field['element'],
            $field['name'],
            $langstr,
            'maxlength="255" size="30"',
        );
        // Field type.
        $mform->setType($field['name'], $field['type']);
        // Setting the default value
        $mform->setDefault($field['name'], $this->actionconfig[$field['name']] ?? $field['default']);
        // Required field.
        $mform->addRule($field['name'], null, 'required', null, 'client');

        if ($field['help']) {
            // If needed, display a help.
            if ($strexists) {
                $mform->addHelpButton($field['name'], "action:{$this->actionname}:{$field['name']}", self::PLUGINNAME);
            } else {
                $mform->addHelpButton($field['name'], $field['name'], self::PLUGINNAME);
            }
        }
    }
}
