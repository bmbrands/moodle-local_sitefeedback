<?php
// This file is part of the Local Analytics plugin for Moodle
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
 * Local feedback
 *
 * This plugin adds a feedback button to any Moodle page.
 *
 * @package    local_sitefeedback
 * @copyright  Bas Brands, Sonsbeekmedia 2020
 * @author     Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_sitefeedback;

defined('MOODLE_INTERNAL') || die;

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use coding_exception;

require_once($CFG->libdir . '/externallib.php');

/**
 * The sitefeedback external services
 *
 * @copyright  Bas Brands, Sonsbeekmedia 2020
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.6
     */
    public static function sendmessage_parameters() {
        return new external_function_parameters([
            'message' => new external_value(PARAM_RAW, 'Message'),
            'emailaddress' => new external_value(PARAM_RAW, 'Sender emailaddress'),
            'like' => new external_value(PARAM_TEXT, 'User like on or off'),
            'includescreenshot' => new external_value(PARAM_TEXT, 'Include screenshot on or off'),
        ]);
    }

    /**
     * Set a user timezone.
     *
     * @param string $timezone New timezone
     *
     * @return array new time and warnings
     */
    public static function sendmessage($message, $emailaddress, $like, $includescreenshot) {
        global $USER, $DB;

        $usercontext = \context_user::instance($USER->id);

        self::validate_context($usercontext);

        $params = self::validate_parameters(self::sendmessage_parameters(), [
            'message' => $message,
            'emailaddress' => $emailaddress,
            'like' => $like,
            'includescreenshot' => $includescreenshot
        ]);

        $message = $params['message'];
        $emailaddress = $params['emailaddress'];
        $like = $params['like'];

        $result = [];
        $result['warning'] = '';
        $result['success'] = 0;

        if (empty($message) || strlen($message) < 10) {
            $result['warning'] .= 'Message too short';
            return $result;
        }
        if (!validate_email($emailaddress)) {
            $result['warning'] .= 'invalid email address';
            return $result;
        }

        $sender = $USER;
        $sender->message = $message;
        if (!isloggedin() || isguestuser()) {
            $sender->email = $emailaddress;
            $sender->firstname = 'anonymous';
            $sender->lastname = 'sender';
        }
        $sender->name = fullname($sender);
        $sender->like = ($like == 'on') ?
            get_string('like', 'local_sitefeedback') : get_string('dislike', 'local_sitefeedback');

        $admin = get_admin();
        $moderator = clone($admin);

        $feedbackadmin = get_config('local_sitefeedback', 'feedbackadmin');
        if (validate_email($feedbackadmin)) {
            $moderator->email = $feedbackadmin;
        }
        $subject = get_string('subject_default', 'local_sitefeedback', $sender);
        $message = get_string('email_default', 'local_sitefeedback', $sender);

        try {
            $send = email_to_user($moderator, $sender, $subject, html_to_text($message), $message);
        } catch (Exception $e) {
            $result['warning'] .= 'Caught exception: '.  $e->getMessage();
        }

        if ($send) {
            $result['success'] = 1;
        } else {
            $result['warning'] .= 'Could not send feedback message';
        }
        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.5
     */
    public static function sendmessage_returns() {
        return new external_single_structure(
            array(
                'warning' => new external_value(PARAM_RAW, 'warning'),
                'success' => new external_value(PARAM_INT, 'success')
            )
        );
    }
}
