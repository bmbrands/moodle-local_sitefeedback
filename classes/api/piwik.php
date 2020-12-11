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

use local_sitefeedback\dimensions;

use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Piwik sitefeedback class.
 * @copyright  Bas Brands, Sonsbeekmedia 2017
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class piwik extends sitefeedback {
    /**
     * Insert the actual tracking code.
     *
     * @return void As the insertion is done through the $CFG->additionalhtmlhead.
     */
    public static function insert_tracking() {
        global $CFG, $USER, $OUTPUT;

        $template = new stdClass();

        $template->imagetrack = get_config('local_sitefeedback', 'imagetrack');
        $template->siteurl = get_config('local_sitefeedback', 'siteurl');
        $template->siteid = get_config('local_sitefeedback', 'siteid');

        // Need to add an option for no tracking.
        $template->userid = $USER->id;
        $cleanurl = get_config('local_sitefeedback', 'cleanurl');

        if (!empty($template->siteurl)) {
            if ($cleanurl) {
                $template->doctitle = "_paq.push(['setDocumentTitle', '".self::trackurl()."']);\n";
            } else {
                $template->doctitle = "";
            }

            if (self::should_track()) {
                $CFG->additionalhtmlhead .= $OUTPUT->render_from_template('local_sitefeedback/piwik', $template);
            }
        }
    }
}
