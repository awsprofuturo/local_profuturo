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

require('../../config.php');
global $CFG;
require_once($CFG->dirroot . '/lib/phpunit/classes/util.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
$datagenerator = phpunit_util::get_data_generator();
$user = $datagenerator->create_user();
profile_save_data((object)['id' => $user->id, 'profile_field_grade' => 'Segundo', 'profile_field_group' => 'B']);
\core\event\user_created::create_from_userid($user->id)->trigger();
if ($user) {
    mtrace("test user created");
}