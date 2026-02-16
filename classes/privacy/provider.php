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

namespace aiprovider_yandexai\privacy;

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\userlist;

/**
 * Privacy Subsystem for Yandex AI provider implementing null_provider.
 *
 * @package    aiprovider_yandexai
 * @copyright  2024 Matt Porritt <matt.porritt@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @codeCoverageIgnore
 */

defined('MOODLE_INTERNAL') || die();

class provider implements
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\core_userlist_provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * @param collection $collection
     * @return collection
     */
    #[\Override]
    public static function get_metadata(collection $collection): collection {
        $collection->add_external_location_link('aiprovider_yandexai', [
            'prompttext' => 'privacy:metadata:aiprovider_yandexai:prompttext',
            'model' => 'privacy:metadata:aiprovider_yandexai:model',
            'numberimages' => 'privacy:metadata:aiprovider_yandexai:numberimages',
            'responseformat' => 'privacy:metadata:aiprovider_yandexai:responseformat',
        ], 'privacy:metadata:aiprovider_yandexai:externalpurpose');
        return $collection;
    }

    /**
     * @param int $userid
     * @return contextlist
     */
    #[\Override]
    public static function get_contexts_for_userid(int $userid): contextlist {
        return new contextlist();
    }

    /**
     * @param userlist $userlist
     * @return void
     */
    #[\Override]
    public static function get_users_in_context(userlist $userlist) {
    }

    /**
     * @param approved_contextlist $contextlist
     * @return void
     */
    #[\Override]
    public static function export_user_data(approved_contextlist $contextlist) {
    }

    /**
     * @param \context $context
     * @return void
     */
    #[\Override]
    public static function delete_data_for_all_users_in_context(\context $context) {
    }

    /**
     * Delete multiple users within a single context.
     *
     * @param approved_userlist $userlist The approved context and user information to delete information for.
     */
    public static function delete_data_for_users(approved_userlist $userlist) {
    }

    /**
     * @param approved_contextlist $contextlist
     * @return void
     */
    #[\Override]
    public static function delete_data_for_user(approved_contextlist $contextlist) {
    }
}
