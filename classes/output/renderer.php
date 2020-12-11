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

namespace local_sitefeedback\output;
defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;
use renderable;

require_once($CFG->dirroot . '/local/sitefeedback/lib.php');

/**
 * Class containing renderable for the sitefeedback button
 *
 * @copyright  Bas Brands, Sonsbeekmedia 2020
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {
    /**
     * Return the button content for the site feedback plugin.
     *
     * @param button $button The button renderable
     * @return string HTML string
     */
    public function render_button(button $button) {
        return $this->render_from_template('local_sitefeedback/button', $button->export_for_template($this));
    }

}