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
 * Folder module version information
 *
 * @package    mod
 * @subpackage ecampusbookpage
 * @copyright  2013 onwards Johan Reinalda {@link http://www.thunderbird.edu}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$module->version   = 2013111100;       // The current module version (Date: YYYYMMDDXX)
$module->requires  = 2012112900;    // Requires this Moodle version
$module->component = 'mod_ecampusbookpage';        // Full name of the plugin (used for diagnostics)
$module->cron      = 0;
$module->release = '1.0';
$module->maturity = MATURITY_STABLE;  // [MATURITY_STABLE | MATURITY_RC | MATURITY_BETA | MATURITY_ALPHA]
$module->dependencies = array(
		'block_ecampus_tbird' => 2013112500,
);