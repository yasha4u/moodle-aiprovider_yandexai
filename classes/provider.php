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

use core_ai\form\action_settings_form;
use Psr\Http\Message\RequestInterface;

/**
 * Class provider.
 *
 * @copyright 2024 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider extends \core_ai\provider {
    /**
     * Список доступных действий для данного провайдера.
     *
     * @return array массив названий классов.
     */
    public static function get_action_list(): array {
        $classes = [];
        // Список действий, которые поддерживает провайдер.
        // Содержит данные в виде массива, где ключ - название действия, а значение - класс действия.
        $actions = [
            'generate_text' => \core_ai\aiactions\generate_text::class,
            'generate_image' => \core_ai\aiactions\generate_image::class,
            'summarise_text' => \core_ai\aiactions\summarise_text::class,
            'explain_text' => \core_ai\aiactions\explain_text::class,
        ];

        foreach ($actions as $componentname => $class) {
            // Если действие активно, добавляем его в список действий в инстансе провайдера.
            if (self::plugin_enabled($componentname)) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    /**
     * Данные для аутентификации
     *
     * @param RequestInterface $request
     * @return RequestInterface
     */
    #[\Override]
    public function add_authentication_headers(RequestInterface $request): RequestInterface {
        return $request->withAddedHeader('Authorization', "Api-Key {$this->config['apikey']}");
    }

    /**
     * Выводим ссылку на форму настройки действия в инстансе провайдера
     *
     * @param string $action
     * @param array $customdata
     * @return action_settings_form|bool
     */
    #[\Override]
    public static function get_action_settings(
        string $action,
        array $customdata = [],
    ): action_settings_form|bool {
        $actionname = substr($action, (strrpos($action, '\\') + 1));
        $customdata['actionname'] = $actionname;
        $customdata['action'] = $action;
        $classname = "\\aiprovider_yandexai\\form\\action_{$actionname}_form";

        if (!class_exists($classname)) {
            return false;
        }

        return new $classname(customdata: $customdata);
    }

    /**
     * @param string $action
     * @return array
     */
    #[\Override]
    public static function get_action_setting_defaults(string $action): array {
        $actionname = substr($action, (strrpos($action, '\\') + 1));
        $customdata = [
            'actionname' => $actionname,
            'action' => $action,
        ];
        $classname = "\\aiprovider_yandexai\\form\\action_{$actionname}_form";

        if (!class_exists($classname)) {
            return [];
        }

        $mform = new $classname(customdata: $customdata);

        return $mform->get_defaults();
    }

    /**
     * Проверяем, что провайдер имеет минимальную конфигурацию для работы.
     *
     * @return bool Return true if configured.
     */
    public function is_provider_configured(): bool {
        return !empty($this->config['apikey']);
    }

    /**
     * Проверяем, включено ли действие
     *
     * @param $action
     * @return bool
     * @throws \coding_exception
     */
    private static function plugin_enabled($componentname): bool {
        // ИИ плагины эдитора
        $editorplugins = ['generate_image', 'generate_text'];

        if (in_array($componentname, $editorplugins)) {
            // Для плагинов эдитора проверяем включено ли размещение "текстовый редактор"
            $componentname = 'aiplacement_editor';
        }

        // ИИ плагины курса
        $courseplugins = ['explain_text', 'summarise_text'];

        if (in_array($componentname, $courseplugins)) {
            // Для плагинов курса проверяем включено ли размещение "Course Assistance"
            $componentname = 'aiplacement_courseassist';
        }

        [$plugintype, $pluginname] = explode('_', \core_component::normalize_componentname($componentname), 2);
        $pluginmanager = \core_plugin_manager::resolve_plugininfo_class($plugintype);

        return $pluginmanager::is_plugin_enabled($pluginname);
    }
}
