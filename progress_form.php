<?php

require_once('../../config.php');
require_once('./rr_form.php');
require_once("$CFG->libdir/outputcomponents.php");
require_once("$CFG->libdir/formslib.php");
require_once("$CFG->libdir/weblib.php");
require_once('./locallib.php');

global $CFG, $PAGE,$DB;

$id = optional_param('id', 0, PARAM_INT); // Course Module ID
$r  = optional_param('n', 0, PARAM_INT);  // ResearchReport ID

$PAGE->set_url($CFG->wwwroot.'//mod/researchreport/activity_form.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('New Activity');
$PAGE->set_heading('New Activity');

// $returnurl = new moodle_url('/mod/researchreport/view.php', array(
//     'id' =>$cm->id
// ));
$returnurl = new moodle_url('/mod/researchreport/view.php?id=59');
$mform = new progress_form();

echo $OUTPUT->header();

echo 'progress';

$mform->display();

if ($mform->is_cancelled()) {
    // You need this section if you have a cancel button on your form
    // here you tell php what to do if your user presses cancel
    // probably a redirect is called for!
    // PLEASE NOTE: is_cancelled() should be called before get_data();
    redirect($returnurl);
    
} else if ($formdata = $mform->get_data()) {
    // This branch is where you process validated data.
    // Do stuff ...
    
    // Typically you finish up by redirecting to somewhere where the user
    // can see what they did.
    
    // 11/25
    // $getentry = new stdClass('activity_data', $entry);
    // $DB->insert_record('rrpost', $getentry, true);
    // $DB->insert_record('rrpost', $inputs);
    
    echo "<pre>";
    echo var_dump($formdata);
    echo "</pre>";
    
//     redirect($returnurl);
    redirect();
}


echo $OUTPUT->footer();

?>