<?php
require_once('../../config.php');
require_once('./rr_form.php');
require_once("$CFG->libdir/outputcomponents.php");
require_once("$CFG->libdir/formslib.php");
require_once("$CFG->libdir/weblib.php");
require_once('./locallib.php');

//pChart include
include($CFG->dirroot.'/mod/researchreport/pChart/class/pData.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pDraw.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pImage.class.php');

global $CFG, $PAGE,$DB;

$userid         = optional_param('userid', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT); // Course Module ID
$r  = optional_param('n', 0, PARAM_INT);  // ResearchReport ID

$PAGE->set_url($CFG->wwwroot.'//mod/researchreport/allactivity.php', array(
    'userid' =>$userid
));

// echo $userid;

// $userid = $USER->id;
$username = $DB->get_records_sql("SELECT username FROM mdl_user WHERE id = $userid ");
$username = key($username);


$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title("$username のページ");
$PAGE->set_heading("$username のページ");

// $returnurl = new moodle_url('/mod/researchreport/view.php', array('id' =>$cm->id));
$returnurl = new moodle_url('/mod/researchreport/view.php?id=59');

echo $OUTPUT->header();

// debug_dump($userid);
// $userid = $USER->id;
// echo $userid;

$userpost = $DB->get_records_sql("select * from mdl_rrpost where userid = $userid");
if ($userpost !=NULL){
    // debug_dump($userpost);
    $userpost = array_merge($userpost,array());
    // debug_dump($userpost);
    
    foreach($userpost as $key => $val);
    // echo $key;
    
    $pacttime = array();
    $pevalution = array();
    for($i=0;$i<=$key;$i++){
        array_push($pacttime,$userpost[$i]->acttime);
        array_push($pevalution,$userpost[$i]->evalution);
    }
    // debug_dump($pevalution);
    
    
    $myData = new pData();
    $myData->addPoints($pacttime,"Serie1");
    $myData->setSerieDescription("Serie1","Activity Time");
    $myData->setSerieOnAxis("Serie1",0);
    
    // $myData->addPoints(array("January","February","March","April","May","June","July","August"),"Absissa");
    // $myData->setAbscissa("Absissa");
    
    $myData->setAxisPosition(0,AXIS_POSITION_LEFT);
    $myData->setAxisName(0,"Time ( h )");
    $myData->setAxisUnit(0,"");
    
    $myPicture = new pImage(1000,300,$myData,TRUE);
    $myPicture->Antialias = FALSE;
    $myPicture->setGraphArea(100,100,800,210);
    $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>18));
    
    $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
        , "Mode"=>SCALE_MODE_START0
        , "LabelingMethod"=>LABELING_ALL
        , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>0, "DrawYLines"=>NONE);
    $myPicture->drawScale($Settings);
    
    $Config = array("DisplayValues"=>0,"AroundZero"=>1);
    $myPicture->drawBarChart($Config);
    
    $Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/pf_arma_five.ttf", "FontSize"=>20, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
        , "Mode"=>LEGEND_HORIZONTAL
    );
    $myPicture->drawLegend(200,16,$Config);
    
    $myPicture->render("personal_activity_time.png");
    
//     echo "<div style='float:left; width:50%;'>
//     <p><img src='personal_activity_time.png' /></div></p>";
    
    ////////////////////////////////////////////////////////////////
    //mix
    $myData = new pData();
    $myData->addPoints($pacttime,"Serie1");
    //                 $myData->setPalette("Serie1", array("R"=>51,"G"=>153,"B"=>150));
    $myData->setSerieDescription("Serie1",Activity);
    $myData->setSerieOnAxis("Serie1",0);
    
    $myData->addPoints($pevalution,"Serie2");
    $myData->setSerieDescription("Serie2","Evalution");
    $myData->setPalette("Serie2", array("R"=>51,"G"=>153,"B"=>150));
    $myData->setSerieOnAxis("Serie2",1);
    
    // $myData->addPoints($pdrawdata->timemd,"Absissa");
    $myData->setAbscissa("Absissa");
    $myData->setAbscissaName("Date");
    
    $myData->setAxisPosition(0,AXIS_POSITION_LEFT);
    $myData->setAxisName(0,"Time( h )");
    $myData->setAxisUnit(0,"");
    
    $myData->setAxisPosition(1,AXIS_POSITION_RIGHT);
    $myData->setAxisName(1,"percent( % )");
    $myData->setAxisUnit(1,"");
    
    $myPicture = new pImage(1000,300,$myData);
    $myPicture->Antialias = FALSE;
    $myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
    $TextSettings = array("Align"=>TEXT_ALIGN_TOPLEFT
        , "R"=>0, "G"=>0, "B"=>0);
    $myPicture->drawText(50,10,"Activity",$TextSettings);
    
    $myPicture->setGraphArea(50,50,800,210);
    $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>16));
    
    $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
        , "Mode"=>SCALE_MODE_MANUAL
        , "ManualScale"=>array(
            0=>array("Min"=>0,"Max"=>12),
            1=>array("Min"=>0,"Max"=>100)
        )
        , "LabelingMethod"=>LABELING_ALL
        , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>0, "DrawYLines"=>NONE);
    $myPicture->drawScale($Settings);
    
    $Config = array("DisplayValues"=>0, "AroundZero"=>1);
    
    $myData->setSerieDrawable("Serie1",TRUE);
    $myData->setSerieDrawable("Serie2",FALSE);
    $myPicture->drawBarChart($Config);
    
    
    $myData->setSerieDrawable("Serie1",FALSE);
    $myData->setSerieDrawable("Serie2",TRUE);
    $myPicture->drawAreaChart($Config);
    
    $myData->setSerieDrawable("Serie1",TRUE);//TRUEに戻しておく
    
    $Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/pf_arma_five.ttf", "FontSize"=>12, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
        , "Mode"=>LEGEND_HORIZONTAL);
    $myPicture->drawLegend(200,16,$Config);
    
    $myPicture->render("mixpersonal.png");
    
    echo "<div style='float:left; width:100%;'>
    <p><img src='mixpersonal.png' /></div></p>";
    
    
    for($key; $key>=0; $key--){
        $date = $userpost[$key]->created;
        $start = $userpost[$key]->sttime;
        $end = $userpost[$key]->edtime;
        $reserch = $userpost[$key]->acttime;
        $study = $userpost[$key]->studytime;
        $evalution = $userpost[$key]->evalution;
        $summary = $userpost[$key]->summary;
        $task = $userpost[$key]->task;
        echo
        "<div style='float:left; width:
        100%;word-wrap: break-word;padding:
        0.5em 1em;margin:
        1em 0;
        background: #f4f4f4;
        border-left: solid 6px #5bb7ae;
        box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.100)'>
        <p>活動内容:<br>$summary</p>
        <p>次回課題:<br>$task</p>
        <p>更新日:$date <br>活動時間:$start 〜 $end <br>
        研究時間:$reserch 時間 <br>勉強時間:$study 時間<br>
        1日の評価:$evalution ％<br>
        </p>
    </div>";
    }
}
else echo 'なにもないよ';
echo $OUTPUT->footer();

function debug_dump($getobject){
    echo "section<br />";
    //オブジェクトクラスの中身を整理し表示する関数
    echo "<pre>";
    echo var_dump($getobject);
    echo "</pre>";
}