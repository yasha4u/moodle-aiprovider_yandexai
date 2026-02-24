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
 * Class provider.
 *
 * @package   aiprovider_yandexai
 * @copyright 2024 LMS-Service {@link https://lms-service.ru/}
 * @author    Ibragim Abdul-Medzhidov
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace aiprovider_yandexai;

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
     * List of available actions for this provider.
     *
     * @return array array of class names.
     */
    public static function get_action_list(): array {
        $classes = [];
        // List of actions supported by the provider.
        // Contains data as an array where the key is the action name and the value is the action class.
        $actions = [
            'generate_text' => \core_ai\aiactions\generate_text::class,
            'generate_image' => \core_ai\aiactions\generate_image::class,
            'summarise_text' => \core_ai\aiactions\summarise_text::class,
            'explain_text' => \core_ai\aiactions\explain_text::class,
        ];

        foreach ($actions as $componentname => $class) {
            // If the action is active, add it to the action list in the provider instance.
            if (self::plugin_enabled($componentname)) {
                $classes[] = $class;
            }
        }

        return $classes;
    }

    /**
     * Authentication data
     *
     * @param RequestInterface $request
     * @return RequestInterface
     */
    #[\Override]
    public function add_authentication_headers(RequestInterface $request): RequestInterface {
        return $request->withAddedHeader('Authorization', "Api-Key {$this->config['apikey']}");
    }

    /**
     * Outputting the link to the action configuration form in the provider instance.
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
     * Get the default settings for the action.
     *
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
     * Checking that the provider has the minimum configuration required to work.
     *
     * @return bool Return true if configured.
     */
    public function is_provider_configured(): bool {
        return !empty($this->config['apikey']);
    }

    /**
     * Checking if the action is enabled.
     *
     * @param $action
     * @return bool
     * @throws \coding_exception
     */
    private static function plugin_enabled($componentname): bool {
        // Editor AI plugins.
        $editorplugins = ['generate_image', 'generate_text'];

        if (in_array($componentname, $editorplugins)) {
            // For editor plugins, checking if the text editor placement is enabled.
            $componentname = 'aiplacement_editor';
        }

        // Course AI plugins.
        $courseplugins = ['explain_text', 'summarise_text'];

        if (in_array($componentname, $courseplugins)) {
            // For course plugins, checking if the "Course Assistance" placement is enabled.
            $componentname = 'aiplacement_courseassist';
        }

        [$plugintype, $pluginname] = explode('_', \core_component::normalize_componentname($componentname), 2);
        $pluginmanager = \core_plugin_manager::resolve_plugininfo_class($plugintype);

        return $pluginmanager::is_plugin_enabled($pluginname);
    }
}
