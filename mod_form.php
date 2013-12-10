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
 * eCampus URL configuration form
 *
 * @package    mod
 * @subpackage ecampusbookpage
 * @copyright  2013 onwards Johan Reinalda {@link http://www.thunderbird.edu}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once ($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/blocks/ecampus_tbird/lib.php');

class mod_ecampusbookpage_mod_form extends moodleform_mod {

	function definition() {
		global $CFG;
	
		$mform =& $this->_form;
		// get module admin settings
		$config = get_config('ecampusbookpage');

		
		//get the list of books for this course
		$booklist = get_eCampus_booklist();
		$bookarray = Array();
		foreach($booklist as $book) {
			$bookarray[$book->isbn] = $book->title;
		}
		if(count($bookarray)) {
		
			//-------------------------------------------------------
			$mform->addElement('header', 'general', get_string('general', 'form'));
			$mform->addElement('text', 'name', get_string('linkname','ecampusbookpage'), array('size'=>'48'));
			if (!empty($CFG->formatstringstriptags)) {
				$mform->setType('name', PARAM_TEXT);
			} else {
				$mform->setType('name', PARAM_CLEANHTML);
			}
			$mform->addRule('name', null, 'required', null, 'client');
			$mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
			//add intro editor, either optional or required depending on admin setting
			$this->add_intro_editor($config->requiremodintro);
			
			//-------------------------------------------------------
			$mform->addElement('header', 'content', get_string('bookheader', 'ecampusbookpage'));
			$mform->addElement('select', 'isbn', get_string('selectbook', 'ecampusbookpage'), $bookarray);
			$mform->addRule('isbn', null, 'required', null, 'client');
			$mform->addHelpButton('isbn', 'isbn', 'ecampusbookpage');
			
			$mform->addElement('text', 'pagenumber', get_string('selectpage', 'ecampusbookpage'), array('size'=>'4'));
			$mform->addRule('pagenumber', null, 'required', null, 'client');
			//-------------------------------------------------------
			
			$this->standard_coursemodule_elements();
		
			$this->add_action_buttons();
		} else {
			
			$mform->addElement('header', 'general', get_string('nobooksfound', 'ecampusbookpage'));
			$mform->addElement('static','nobooksdescription','',get_string('booknotfound','ecampusbookpage'));
			$this->standard_coursemodule_elements();
		}
	}
	
}
