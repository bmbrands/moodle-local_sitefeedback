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

$string['pluginname'] = 'Site Feedback';
$string['sitefeedback'] = 'Site Feedback';
$string['enabled'] = 'Feedback enabled';
$string['enabled_desc'] = 'Turn the feedback button off or on';
$string['externallinks'] = 'External links';
$string['externallinks_desc'] = 'Button links to show in the feedback modal. Each link item on a new line with format: link text, link URL, fontAwesome icon name. For example:
<pre>
Help and Support|http://example.com/support|fa-help
Report a bug|http://example.com/bugs|fa-bug
</pre>
';
$string['emailaddress'] = 'Email (optional)';
$string['headingpositive'] = 'What did you like?';
$string['headingnegative'] = 'What did you not like?';
$string['like'] = 'I like something';
$string['dislike'] = 'I don\'t like something';
$string['actionsheader'] = 'What kind of feedback do you have?';
$string['feedback'] = 'Feedback';
$string['includescreenshot'] = 'Include screenshot';
$string['yourfeedback'] = 'Your Feedback';
$string['subject'] = 'Email subject';
$string['subject_default'] = 'Feedback received from: {$a->name}';
$string['subject_desc'] = 'Subject used for feedback emails';
$string['email'] = 'Email format';
$string['email_default'] = '
<html>
<body>
<table cellspacing="0" cellpadding="8">
<tr><td colspan="2"><h3>{$a->like} : </h3>
		message:<br>
		{$a->message}
   	</td>
</tr>
<tr><td>Username: </td><td>{$a->username}</td></tr>
<tr><td>Email: </td><td>{$a->email}</td></tr>
</table>
</body>
</html>
';
$string['email_desc'] = 'Customise the email message send to the feedback admin';

$string['feedbackadmin'] = 'Send feedback to';
$string['feedbackadmin_desc'] = 'When new users log in this email address is used to send a notification message, users will be able to see this email address';
