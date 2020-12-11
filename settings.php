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

defined('MOODLE_INTERNAL') || die;

if (is_siteadmin()) {

    $moderator = get_admin();

    $settings = new admin_settingpage('local_sitefeedback', get_string('pluginname', 'local_sitefeedback'));
    $ADMIN->add('localplugins', $settings);

    $name = 'local_sitefeedback/enabled';
    $title = get_string('enabled', 'local_sitefeedback');
    $description = get_string('enabled_desc', 'local_sitefeedback');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $settings->add($setting);

    $name = 'local_sitefeedback/externallinks';
    $title = get_string('externallinks', 'local_sitefeedback');
    $description = get_string('externallinks_desc', 'local_sitefeedback');
    $setting = new admin_setting_configtextarea($name, $title, $description, '', PARAM_RAW, '50', 4);
    $settings->add($setting);

    $name = 'local_sitefeedback/feedbackadmin';
    $title = get_string('feedbackadmin', 'local_sitefeedback');
    $description = get_string('feedbackadmin_desc', 'local_sitefeedback');
    $setting = new admin_setting_configtext($name, $title, $description, $moderator->email);
    $settings->add($setting);

    // $default = get_string('subject_default', 'local_sitefeedback');
    // $name = 'local_sitefeedback/subject';
    // $title = get_string('subject', 'local_sitefeedback');
    // $description = get_string('subject_desc', 'local_sitefeedback');
    // $setting = new admin_setting_configtext($name, $title, $description, $default);
    // $settings->add($setting);

    // $default = get_string('email_default', 'local_sitefeedback');
    // $name = 'local_sitefeedback/email';
    // $title = get_string('email', 'local_sitefeedback');
    // $description = get_string('email_desc', 'local_sitefeedback');
    // $setting = new admin_setting_confightmleditor($name, $title, $description, $default);
    // $settings->add($setting);
}