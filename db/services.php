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
 * Web service local plugin template external functions and service definitions.
 *
 * @package    local_video_directory
 * @copyright  2018 Yedidia Klein - OpenApp ISRAEL
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

// We defined the web service functions to install.
$functions = array(
        'local_video_directory_edit' => array(
                'classname'   => 'local_video_directory_external',
                'methodname'  => 'edit',
                'description' => 'Allow ajax editing of video name and privacy',
                'type'        => 'write',
                'ajax'        => true,
                'capabilities'  => 'local/video_directory:video'
        ),
        'local_video_directory_thumb' => array(
                'classname'   => 'local_video_directory_external',
                'methodname'  => 'thumb',
                'description' => 'Generate thumbs from video',
                'type'        => 'write',
                'ajax'        => true,
                'capabilities'  => 'local/video_directory:video'
        ),
        'local_video_directory_videolist' => array(
                'classname'   => 'local_video_directory_external',
                'methodname'  => 'videolist',
                'description' => 'Generate json of videos',
                'type'        => 'read',
                'ajax'        => true,
                'capabilities'  => 'local/video_directory:video'
        ),
        'local_video_directory_userslist' => array(
                'classname'   => 'local_video_directory_external',
                'methodname'  => 'userslist',
                'description' => 'Generate json of users',
                'type'        => 'read',
                'ajax'        => true,
                'capabilities'  => 'local/video_directory:video'
        )

);
// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = array(
        'Video Directory Services' => array(
                'functions' => array ('local_video_directory_edit',
                                      'local_video_directory_thumb',
                                      'local_video_directory_videolist',
                                      'local_video_directory_userslist'),
                'restrictedusers' => 0,
                'enabled' => 1,
        )
);
