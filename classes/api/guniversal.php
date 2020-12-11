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
 * Analytics
 *
 * This module provides extensive sitefeedback on a platform of choice
 * Currently support Google Analytics and Piwik
 *
 * @package    local_sitefeedback
 * @copyright  Bas Brands, Sonsbeekmedia 2017
 * @author     Bas Brands <bas@sonsbeekmedia.nl>, David Bezemer <info@davidbezemer.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_sitefeedback\api;

use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Guniversal sitefeedback class.
 * @copyright  Bas Brands, Sonsbeekmedia 2017
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class guniversal extends sitefeedback {
    /**
     * Insert the actual tracking code.
     *
     * @return void As the insertion is done through the {js} template API.
     */
    public static function insert_tracking() {
        global $PAGE, $OUTPUT;

        $template = new stdClass();

        $template->sitefeedbackid = get_config('local_sitefeedback', 'sitefeedbackid');
        $cleanurl = get_config('local_sitefeedback', 'cleanurl');

        if ($cleanurl) {
            $template->addition = "{'hitType' : 'pageview',
                'page' : '".self::trackurl(true, true)."',
                'title' : '".addslashes(format_string($PAGE->heading))."'
                }";
        } else {
            $template->addition = "'pageview'";
        }
        if (get_config('local_sitefeedback', 'anonymizeip')) {
            $template->anonymizeip = true;
        }
        if (self::should_track() && !empty($template->sitefeedbackid)) {
            // The templates only contains a "{js}" block; so we don't care about
            // the output; only that the $PAGE->requires are filled.
            $OUTPUT->render_from_template('local_sitefeedback/guniversal', $template);
        }
    }
}
