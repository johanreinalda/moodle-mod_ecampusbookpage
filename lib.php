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
 * Mandatory public API of eCampus Book Page module
 *
 * @package    mod
 * @subpackage ecampusbookpage
 * @copyright  2013 onwards Johan Reinalda {@link http://www.thunderbird.edu}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * List of features supported in eCampus Book Page module
 * @param string $feature FEATURE_xx constant for requested feature
 * @return mixed True if module supports feature, false if not, null if doesn't know
 */
function ecampusbookpage_supports($feature) {
	switch($feature) {
		case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
		case FEATURE_GROUPS:                  return false;
		case FEATURE_GROUPINGS:               return false;
		case FEATURE_GROUPMEMBERSONLY:        return true;
		case FEATURE_MOD_INTRO:               return true;
		case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
		case FEATURE_GRADE_HAS_GRADE:         return false;
		case FEATURE_GRADE_OUTCOMES:          return false;
		case FEATURE_BACKUP_MOODLE2:          return true;
		case FEATURE_SHOW_DESCRIPTION:        return true;

		default: return null;
	}
}

/**
 * Returns all other caps used in module
 * @return array
 */
function ecampusbookpage_get_extra_capabilities() {
	return array('moodle/site:accessallgroups');
}

/**
 * Add ecampusbookpage instance.
 *
 * @param stdClass $data
 * @param stdClass $mform
 * @return int new ecampusbookpage instance id
 */
function ecampusbookpage_add_instance($data, $mform) {
	global $DB;
	
	$data->timecreated = time();
	$data->timemodified = $data->timecreated;
	//read form data: no need here!
		
	return $DB->insert_record('ecampusbookpage', $data);
	
}

/**
 * Update ecampusbookpage instance.
 *
 * @param stdClass $data
 * @param stdClass $mform
 * @return int new ecampusbookpage instance id
 */
function ecampusbookpage_update_instance($data, $mform) {
	global $DB;
	
	$data->timemodified = time();
	$data->id = $data->instance;
	//read form data - not needed
	
	$DB->update_record('ecampusbookpage', $data);

	return true;
}

/**
 * Delete ecampusbookpage instance by activity id
 *
 * @param int $id
 * @return bool success
 */
function ecampusbookpage_delete_instance($id) {
global $DB;
	
	if (!$bookpage = $DB->get_record('ecampusbookpage', array('id'=>$id))) {
		return false;
	}
	// note: all context files are deleted automatically
	
	$DB->delete_records('ecampusbookpage', array('id'=>$book->id));
	
	return true;	
}