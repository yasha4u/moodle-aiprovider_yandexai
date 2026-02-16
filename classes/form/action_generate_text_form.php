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
 * @copyright 2025 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_yandexai\form;

defined('MOODLE_INTERNAL') || die();

/**
 * Класс формы настройки для генерации текста.
 */
class action_generate_text_form extends base_form {
    /**
     * @return void
     * @throws \coding_exception
     */
    #[\Override]
    protected function definition() {
        parent::definition();
        $mform = $this->_form;

        // Использовать html ответы
        $mform->addElement(
            'checkbox',
            'allowhtml',
            get_string("action:{$this->actionname}:allowhtml", self::PLUGINNAME),
        );
        $mform->setDefault('allowhtml', $this->actionconfig['allowhtml'] ?? false);

        $this->set_data($this->actionconfig);
    }
}
