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

defined('MOODLE_INTERNAL') || die();

/**
 * Class injector
 *
 * @package    local_sitefeedback
 * @copyright  Bas Brands, Sonsbeekmedia 2020
 * @author     Bas Brands <bas@sonsbeekmedia.nl>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class injector {
    /** @var bool Keeps state for injection */
    private static $injected = false;

    /**
     * Do the actual sitefeedback code injection.
     *
     * @return null
     */
    public static function inject() {
        global $PAGE;
        if (self::$injected) {
            return;
        }
        self::$injected = true;

        $sitefeedbackenabled = get_config('local_sitefeedback', 'enabled');
        if (!$sitefeedbackenabled) {
            return;
        }
        $renderable = new \local_sitefeedback\output\button();
        $renderer = $PAGE->get_renderer('local_sitefeedback');
        return $renderer->render($renderable);
    }

    /**
     * Toggle the state back to un-injected.
     *
     * @return null
     */
    public static function reset() {
        self::$injected = false;
    }
}
