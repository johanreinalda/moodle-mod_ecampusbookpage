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
 * @package mod
 * @subpackage mod_ecampusbookpage
 * @copyright 2013 onwards Johan Reinalda {@link http://www.thunderbird.edu}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the restore steps that will be used by the restore_ecampusbookpage_activity_task
 */

/**
 * Structure step to restore one url activity
 */
class restore_ecampusbookpage_activity_structure_step extends restore_activity_structure_step {

	protected function define_structure() {
	
		$paths = array();
		$paths[] = new restore_path_element('ecampusbookpage', '/activity/ecampusbookpage');
	
		// Return the paths wrapped into standard activity structure
		return $this->prepare_activity_structure($paths);
	}
	
	protected function process_ecampusbookpage($data) {
		global $DB;
	
		$data = (object)$data;
		$oldid = $data->id;
		$data->course = $this->get_courseid();
	
		// insert the url record
		$newitemid = $DB->insert_record('ecampusbookpage', $data);
		// immediately after inserting "activity" record, call this
		$this->apply_activity_instance($newitemid);
	}
	
	protected function after_execute() {
		// Add url related files, no need to match by itemname (just internally handled context)
		$this->add_related_files('mod_ecampusbookpage', 'intro', null);
	}
}
