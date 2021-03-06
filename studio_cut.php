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
 * Cut videos.
 *
 * @package    local_video_directory
 * @copyright  2017 Yedidia Klein <yedidia@openapp.co.il>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once( __DIR__ . '/../../config.php');
require_login();
defined('MOODLE_INTERNAL') || die();
require_once('locallib.php');
require_once("$CFG->libdir/formslib.php");

$id = optional_param('video_id', 0, PARAM_INT);

$PAGE->set_context(context_system::instance());
$PAGE->set_heading(get_string('studio','local_video_directory'));
$PAGE->set_title(get_string('studio','local_video_directory'));
$PAGE->set_url('/local/video_directory/studio_cut.php?video_id=' . $id);
$PAGE->navbar->add(get_string('pluginname','local_video_directory'), new moodle_url('/local/video_directory/'));
$PAGE->navbar->add(get_string('studio', 'local_video_directory'), new moodle_url('/local/video_directory/studio.php?video_id=' . $id));
$PAGE->navbar->add(get_string('cut','local_video_directory'));

class videocut_form extends moodleform {
    //Add elements to form
    public function definition() {

        $mform = $this->_form; // Don't forget the underscore! 


        $id = optional_param('video_id', 0, PARAM_INT);
        
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);


		for ($i=0; $i<100; $i++) {
			$seconds[]=$i;
		}

		$select = $mform->addElement('select', 'secbefore', get_string('before','local_video_directory'), $seconds);
		$select->setMultiple(false);

		$select = $mform->addElement('select', 'secafter', get_string('after','local_video_directory'), $seconds);
		$select->setMultiple(false);

		$mform->addElement('select', 'save', get_string('save', 'moodle'),
		[ 'version' => get_string('newversion', 'local_video_directory'), 
		  'new' => get_string('newvideo', 'local_video_directory')
		]);

      		$buttonarray=array();
			$buttonarray[] =& $mform->createElement('submit', 'submitbutton', get_string('savechanges'));
			$buttonarray[] =& $mform->createElement('cancel', 'cancel', get_string('cancel'));
			$mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
			
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

//Instantiate simplehtml_form 
$mform = new videocut_form();
 
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/video_directory/studio.php?video_id=' . $id);
} else if ($fromform = $mform->get_data()) {
  //In this case you process validated data. $mform->get_data() returns data posted in form.
    $now = time();
   	$record = array("video_id" => $fromform->id, 
                    "user_id" => $USER->id,
                    "save" => $fromform->save,
                    "state" => 0,
                    "datecreated" => $now,
                    "datemodified" => $now,
   					"secbefore" => $fromform->secbefore,
   					"secafter" => $fromform->secafter,
    );

    $id = $DB->insert_record("local_video_directory_cut",$record);
    redirect($CFG->wwwroot . '/local/video_directory/studio.php?video_id=' . $fromform->id,
                get_string('inqueue', 'local_video_directory'));
} else {
  // this branch is executed if the form is submitted but the data doesn't validate and the form should be redisplayed
  // or on the first display of the form.
  //Set default data (if any)
  //  $mform->set_data($toform);
  //displays the form
    echo $OUTPUT->header();
    $video = $DB->get_record('local_video_directory', array("id" => $id));
    $streaming = get_streaming_server_url() . "/" . $id . ".mp4";
    echo $OUTPUT->render_from_template('local_video_directory/video_float',
    ['wwwroot' => $CFG->wwwroot,  'id' => $id,
    'thumb' => str_replace("-", "&second=", $video->thumb),
    'streaming' => $streaming ]);
    echo "<div>";	
    $mform->display();
    echo "</div>";	
}


echo $OUTPUT->footer();
