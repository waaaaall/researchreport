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

$PAGE->set_url($CFG->wwwroot.'//mod/researchreport/allactivity.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('All Activity');
$PAGE->set_heading('All Activity');

// $returnurl = new moodle_url('/mod/researchreport/view.php', array('id' =>$cm->id));
$returnurl = new moodle_url('/mod/researchreport/view.php?id=59');

echo $OUTPUT->header();

// debug_dump($USER);
$allpost = $DB->get_records_sql("SELECT * FROM mdl_rrpost");
// debug_dump($allpost);
foreach($allpost as $key => $val);

for($key; $key>=0; $key--){
    $userid = $allpost[$key]->userid;
    $username = $DB->get_records_sql("SELECT username FROM mdl_user WHERE id = $userid ");
    $username = key($username);
    $date = $allpost[$key]->created;
    $start = $allpost[$key]->sttime;
    $end = $allpost[$key]->edtime;
    $reserch = $allpost[$key]->acttime;
    $study = $allpost[$key]->studytime;
    $evalution = $allpost[$key]->evalution;
    $summary = $allpost[$key]->summary;
    $task = $allpost[$key]->task;
    echo 
    "<div style='float:left; width:
        100%;word-wrap: break-word;padding:
        0.5em 1em;margin: 
        1em 0;
        background: #f4f4f4;
        border-left: solid 6px #5bb7ae;
        box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.100)'>
        <p>ユーザー:$username <br>活動内容:$summary <br>次回課題:$task</p>
        <p>更新日:$date <br>活動時間:$start 〜 $end <br>
        研究時間:$reserch 時間 <br>勉強時間:$study 時間<br>
        1日の評価:$evalution ％<br>
        </p>
    </div>";
}

echo $OUTPUT->footer();

function debug_dump($getobject){
    echo "section<br />";
    //オブジェクトクラスの中身を整理し表示する関数
    echo "<pre>";
    echo var_dump($getobject);
    echo "</pre>";
}