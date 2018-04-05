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

// $PAGE->set_url($CFG->wwwroot.'//mod/researchreport/activity_form.php');
$PAGE->set_url($CFG->wwwroot.'//mod/researchreport/activity_form.php', array(
    'userid' =>$userid
));
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('New Activity');
$PAGE->set_heading('New Activity');

// $returnurl = new moodle_url('/mod/researchreport/view.php', array('id' =>$cm->id));
$returnurl = new moodle_url('/mod/researchreport/view.php?id=59');
$mform = new activity_form();
// $mform = new test_form();


echo $OUTPUT->header();

echo 'activity';

if ($mform->is_cancelled()) {
    // You need this section if you have a cancel button on your form
    // here you tell php what to do if your user presses cancel
    // probably a redirect is called for!
    // PLEASE NOTE: is_cancelled() should be called before get_data();
    redirect($returnurl);
    
    //  } else if ($entry = $mform->get_data()) {
} else if ($fromform = $mform->get_data()) {
    // This branch is where you process validated data.
    // Do stuff ...
    
    // Typically you finish up by redirecting to somewhere where the user
    // can see what they did.
    
    //activityフォームの取得
    //作成日の取得、追加
    
    debug_dump($fromform);
    
    $insertdata = new stdClass();
    $insertdata->userid = $USER->id;
    $insertdata->summary = $fromform->summary['text'];
    $insertdata->task = $fromform->task['text'];
    $insertdata->sttime = $fromform->sttime;
    $insertdata->edtime = $fromform->edtime;
//     $insertdata->sttime = "9";
//     $insertdata->edtime = "18";
    $insertdata->acttime = $fromform->acttime;
    $insertdata->studytime = $fromform->studytime;
    $insertdata->evalution = $fromform->evalution;   
    $createdtime = new DateTime("now", core_date::get_server_timezone_object());
//     $timestamp = time() ;
    // date()で日時を出力
    $insertdata->timemd = date( "md" ) ;
    var_dump($createdtime);
    $insertdata->created =  $createdtime->date;
    $insertdata->summaryformat = $fromform->summary['format'];
    
    debug_dump($insertdata);
    
    $DB->insert_record('rrpost', $insertdata);
    
    //モジュールトップへリダイレクト
    redirect($returnurl);
    
    //debug
    //DBに挿入されたオブジェクトを確認する場合
//     redirect();
    
}

$mform->display();

echo $OUTPUT->footer();

function debug_dump($getobject){
    echo "section<br />";
    //オブジェクトクラスの中身を整理し表示する関数
    echo "<pre>";
    echo var_dump($getobject);
    echo "</pre>";
}

?>