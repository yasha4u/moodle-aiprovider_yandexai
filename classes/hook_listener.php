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
 * Hook listener for Yandex AI provider.
 *
 * @package   aiprovider_yandexai
 * @copyright 2025 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_yandexai;

use core_ai\hook\after_ai_provider_form_hook;

/**
 * Hook listener for Yandex AI provider.
 */
class hook_listener {
    /**
     * Hook listener for the Yandex AI instance setup form.
     *
     * @param after_ai_provider_form_hook $hook
     * @return void
     * @throws \coding_exception
     */
    public static function set_form_definition_for_aiprovider_yandexai(after_ai_provider_form_hook $hook): void {
        if ($hook->plugin !== 'aiprovider_yandexai') {
            return;
        }

        $mform = $hook->mform;

        // YandexAI API key.
        $mform->addElement(
            'passwordunmask',
            'apikey',
            get_string('apikey', 'aiprovider_yandexai'),
            ['size' => 75],
        );
        $mform->addHelpButton('apikey', 'apikey', 'aiprovider_yandexai');
        $mform->addRule('apikey', get_string('required'), 'required', null, 'client');
    }
}
