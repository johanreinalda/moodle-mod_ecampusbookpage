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
 * handle eCampus book page urls in course
*
* @package    mod
* @subpackage ecampusbookpage
* @copyright  2013 onwards Johan Reinalda (http://www.thunderbird.edu)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require_once('../../config.php');
require_once('lib.php');
require_once($CFG->dirroot.'/blocks/ecampus_tbird/lib.php');

$id = required_param('id', PARAM_INT); // Course Module ID

if (!$cm = get_coursemodule_from_id('ecampusbookpage', $id)) {
	print_error('Course Module ID was incorrect');
}
if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
	print_error('course is misconfigured');
}
//security checks
require_course_login($course, true, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/ecampusbookpage:view', $context);

//get book page information
if (!$bookpage = $DB->get_record('ecampusbookpage', array('id'=> $cm->instance))) {
	print_error('course module is incorrect');
}

//check that we can get the book info
$booklist = get_eCampus_booklist();
$book = false;
foreach($booklist as $b) {
	if($b->isbn == $bookpage->isbn) {
		$book = $b;
		break;
	}
}

//add_to_log($course->id, 'ecampusbookpage', 'view page', 'course='.$course->id.'&amp;isbn='.$bookpage->isbn.'&amp;pagenumber='.$bookpage->pagenumber);
$event = \mod_ecampusbookpage\event\course_module_viewed::create(array(
        'objectid' => $cm->id,
        'context' => $context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->trigger();

//start html
$PAGE->set_url('/mod/ecampusbookpage/view.php', array('id' => $id));
$PAGE->set_title($course->shortname.': '.$bookpage->name);
$PAGE->set_heading($course->fullname);
$PAGE->set_activity_record($bookpage);

if(!$book) {
	// probably because course was restored and now has different or unset eCampus Course ID
	// or different books assigned! Handle this properly!
	echo $OUTPUT->header();
	echo $OUTPUT->heading(format_string($bookpage->name), 2, 'main', 'urlheading');
	echo $OUTPUT->box_start('mod_introbox', 'urlintro');
	echo get_string('booknotfound','ecampusbookpage');
	echo $OUTPUT->box_end();
	echo $OUTPUT->footer();
	exit;
}

// Update 'viewed' state if required by completion system
// needs to be called before header is printed
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($bookpage->name), 2, 'main', 'urlheading');

//get eCampus access information
$studentid = get_eCampus_studentid();	// can be email, username or idnumber
$courseid = get_eCampus_courseid($course->id); // can be idnumber or shortname
//get the eCampus pass-through temporary access code
$error;
$accesscode = get_eCampus_accesscode($studentid,$error);

//and now render the page with the login form
if($accesscode) {
	//show page description if set
	if (trim(strip_tags($bookpage->intro))) {
		echo $OUTPUT->box_start('mod_introbox', 'urlintro');
		echo format_module_intro('bookpage', $bookpage, $cm->id);
		echo $OUTPUT->box_end();
	}
	echo '<p>' . get_string('clickhereforpage', 'mod_ecampusbookpage') . '&nbsp;' . $bookpage->pagenumber . '&nbsp;';
	echo get_string('inbook', 'mod_ecampusbookpage') . '&nbsp;&quot;' . $book->title . '&quot;</p>';
	//render eCampus login form, force submit to go to new window
	$buttontext = get_string('openpage', 'mod_ecampusbookpage');
	echo render_eCampus_login($studentid,$accesscode,$courseid,$bookpage->isbn,$bookpage->pagenumber,$buttontext,$book->secureimage,true,false);
	add_to_log($course->id, 'ecampusbookpage','login','blocks/ecampus_tbird/README.TXT','eCampus Login');

} else {
	// unrecoverable errors have occured, change title!
	echo render_eCampus_error(get_string('erroroccured','block_ecampus_tbird'),$error);
//	add_to_log($course->id, 'ecampusbookpage','error','blocks/ecampus_tbird/README.TXT',substr($error,0,200));
// YET TO BE RECODED WITH EVENT SYSTEM !!!
	
}
echo $OUTPUT->footer();
