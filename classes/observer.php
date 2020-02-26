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

namespace local_profuturo;
/**
 * Plugin observer classes are defined here.
 *
 * @package     local_profuturo
 * @category    event
 * @copyright   2020 Alberto Lara Hernández <alberto.lara@outlook.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event observer class.
 *
 * @package    local_profuturo
 * @copyright  2020 Alberto Lara Hernández <alberto.lara@outlook.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {

    /**
     * Triggered via $event.
     *
     * @param \core\event\user_created $event The event.
     * @return bool True on success.
     */
    public static function user_created($event) {
        global $CFG, $DB;
        require_once("$CFG->dirroot/cohort/lib.php");
        $userid = $event->get_data()['objectid'];
        $profilefields = profile_get_user_fields_with_data($userid);
        $grade = '';
        $group = '';
        foreach($profilefields as $profilefield) {
            if ($profilefield->inputname == 'profile_field_grade') {
                $grade = $profilefield->data;
            }
            if ($profilefield->inputname == 'profile_field_group') {
                $group = $profilefield->data;
            }
        }

        if ($group && $grade) {
            $name = $grade . ' ' . $group;
            $idnumber = str_replace(' ', '', \core_text::strtolower($name));
            $cohortid = $DB->get_field('cohort', 'id', ['idnumber' => $idnumber]);
            if (!$cohortid) {
                $cohortid = cohort_add_cohort((object)array(
                    'idnumber' => $idnumber,
                    'name' => $name,
                    'contextid' => \context_system::instance()->id
                ));
            }
            cohort_add_member($cohortid, $userid);
        }

        return true;
    }
}
