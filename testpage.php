<?php
require_once('../../config.php');
require_once('./rr_form.php');
require_once("$CFG->libdir/outputcomponents.php");
require_once("$CFG->libdir/formslib.php");
require_once("$CFG->libdir/weblib.php");
require_once('./locallib.php');

include($CFG->dirroot.'/mod/researchreport/pChart/class/pData.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pDraw.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pImage.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pScatter.class.php');

global $CFG, $PAGE,$DB;

// $userid = optional_param('userid', $USER->id, PARAM_INT); // Course Module ID
$id = optional_param('id', 0, PARAM_INT); // Course Module ID
$r  = optional_param('n', 0, PARAM_INT);  // ResearchReport ID

$PAGE->set_url($CFG->wwwroot.'//mod/researchreport/testpage.php');
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_title('testpage');
$PAGE->set_heading('testpage');

// $returnurl = new moodle_url('/mod/researchreport/view.php', array('id' =>$cm->id));
$returnurl = new moodle_url('/mod/researchreport/view.php?id=59');

echo $OUTPUT->header();

// debug_dump($USER);

$alluserid = $DB->get_records_sql("SELECT DISTINCT userid FROM mdl_rrpost");
//     debug_dump($alluserid);
$alluserid = array_keys($alluserid);
debug_dump($alluserid);

foreach($alluserid as $key_1 => $val_1){
    $getdata = $DB->get_records_sql("SELECT * FROM mdl_rrpost WHERE userid = $val_1");
    $getdata = array_merge($getdata,array());
    foreach($getdata as $key_2 => $val_2);
    $cmpdata[$key_1]->date = $getdata[$key_2]->created;
    $cmpdata[$key_1]->userid = $getdata[$key_2]->userid;
    $cmpdata[$key_1]->sumtime = $getdata[$key_2]->acttime + $getdata[$key_2]->studytime;
    $cmpdata[$key_1]->acttime = $getdata[$key_2]->acttime;
    $cmpdata[$key_1]->studytime = $getdata[$key_2]->studytime;
}


foreach($cmpdata as $key =>$val){
    $sort[$key] = $val->date;
}
array_merge($sort);
array_multisort($sort, SORT_DESC, $cmpdata);

debug_dump($cmpdata);

$pdrawdata = new stdClass();
$pdrawdata->userid = array();
$pdrawdata->sumtime = array();
$pdrawdata->acttime = array();
$pdrawdata->studytime = array();
$pdrawdata->username = array();

foreach($cmpdata as $key => $val){
// for($key; $key>=0; $key--){
    array_push($pdrawdata->userid, $cmpdata[$key]->userid);
    $userid = $cmpdata[$key]->userid;
    $username = $DB->get_records_sql("SELECT username FROM mdl_user WHERE id = $userid");
    $username = key($username);
    array_push($pdrawdata->username, $username);
    array_push($pdrawdata->sumtime, $cmpdata[$key]->sumtime);
    array_push($pdrawdata->acttime, $cmpdata[$key]->acttime);
    array_push($pdrawdata->studytime, $cmpdata[$key]->studytime);
}

debug_dump($pdrawdata);


$myData = new pData();
$myData->addPoints($pdrawdata->acttime,"Serie1");
$myData->setSerieDescription("Serie1","Serie 1");
$myData->setSerieOnAxis("Serie1",0);

$myData->addPoints($pdrawdata->studytime,"Serie2");
$myData->setSerieDescription("Serie2","Serie 2");
$myData->setSerieOnAxis("Serie2",0);

$myData->addPoints($pdrawdata->username,"Absissa");
$myData->setAbscissa("Absissa");

$myData->setAxisPosition(0,AXIS_POSITION_LEFT);
$myData->setAxisName(0,"1st axis");
$myData->setAxisUnit(0,"");

$myPicture = new pImage(900,500,$myData,TRUE);
$myPicture->Antialias = FALSE;
$myPicture->setGraphArea(150,50,675,400);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>16));

// $Settings = array("Pos"=>SCALE_POS_TOPBOTTOM
//     , "Mode"=>SCALE_MODE_MANUAL
//     , "ManualScale"=>array(
//         0=>array("Min"=>0,"Max"=>20))
//     , "LabelingMethod"=>LABELING_ALL
//     , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, 
//     "TickAlpha"=>50, "LabelRotation"=>5, 
//     "DrawArrows"=>1, "DrawXLines"=>0, "DrawSubTicks"=>0,
//     "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>0,"DrawYLines"=>ALL
// );
// $myPicture->drawScale($Settings);

$scatter = new pScatter($myPicture, $myData);

$scatter->drawScatterScale(array(
    // それぞれの軸の最小値と最大値を手動で決めます。
    "Mode" => SCALE_MODE_MANUAL,
    "ManualScale" => array(
        0 => array("Min" => 0, "Max" => 15),
        1 => array("Min" => -10, "Max" => 20),
        2 => array("Min" => 0, "Max" => 10),
    ),
    // X軸ラベルが回転しないようにします。
    "XLabelsRotation" => 0,
    // グリッド線の色を変えます。
    "GridR" => 180, "GridG" => 180, "GridB" => 180,
));

$scatter->drawScatterLineChart();
$scatter->drawScatterPlotChart();

$scatter->drawScatterLegend(720, 400, array("Mode" => LEGEND_HORIZONTAL, "Style" => LEGEND_NOBORDER));

// $Config = array("AroundZero"=>0);
// // $myPicture->drawBarChart($Config);
// $myPicture->drawStackedBarChart($Config);

// $Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/pf_arma_five.ttf", "FontSize"=>16, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
//     , "Mode"=>LEGEND_HORIZONTAL
// );
// $myPicture->drawLegend(648,16,$Config);

$myPicture->render("tatechart.png");

//////////////////////////////////////////////////////////////////////////////////////////////////
$data = new pData();
// Y軸用に2種類の適当なデータを設定します。
$data->addPoints($pdrawdata->acttime,  "Y1");
$data->addPoints($pdrawdata->studytime,  "Y2");
// データの色を設定します。
$data->setPalette("Y1", array("R" => 130, "G" => 200, "B" => 0));
$data->setPalette("Y2", array("R" => 0, "G" => 130, "B" => 200));
// X軸用に2種類の適当なデータを設定します。
$data->addPoints($pdrawdata->username,  "X1");
// $data->addPoints(array(0, 1,  3,  3.5,  4.3,  5.7,  6.1,  7,  8,  8.5),  "X2");
// 0番目の軸をY軸として設定します。
$data->setSerieOnAxis("Y1", 0);
$data->setAxisName(0, "Y Axis");
$data->setAxisXY(0, AXIS_Y);
$data->setAxisPosition(0, AXIS_POSITION_TOP);
// 1番目の軸をX1軸として設定します。
$data->setSerieOnAxis("X1", 1);
$data->setAxisName(1, "X1 Axis");
$data->setAxisXY(1, AXIS_X);
$data->setAxisPosition(1, AXIS_POSITION_RIGHT);
// 2番目の軸をX2軸として設定します。
// $data->setSerieOnAxis("X2", 2);
// $data->setAxisName(2, "X2 Axis");
// $data->setAxisXY(2, AXIS_X);
// $data->setAxisPosition(2, AXIS_POSITION_LEFT);
// それぞれのデータの系列と系列名を設定します。
$data->setScatterSerie("X1", "Y1", 0);
$data->setScatterSerieDescription(0, "X1 - Y1");
// $data->setScatterSerie("X2", "Y2", 1);
// $data->setScatterSerieDescription(1, "X2 - Y2");
$image = new pImage(900, 450, $data);
$image->setFontProperties(array("FontName" => "../fonts/pf_arma_five.ttf", "FontSize" => 18));
$image->setGraphArea(50, 50, 700, 300);
$scatter = new pScatter($image, $data);
$scatter->drawScatterScale(array(
    // それぞれの軸の最小値と最大値を手動で決めます。
    "Mode" => SCALE_MODE_MANUAL,
    "ManualScale" => array(
        0 => array("Min" => 0, "Max" => 10),
        1 => array("Min" => -10, "Max" => 20),
        2 => array("Min" => 0, "Max" => 10),
    ),
    // X軸ラベルが回転しないようにします。
    "XLabelsRotation" => 0,
    // グリッド線の色を変えます。
    "GridR" => 180, "GridG" => 180, "GridB" => 180,
));
// 折れ線とプロットを描画します。
$scatter->drawScatterLineChart();
$scatter->drawScatterPlotChart();
// 凡例を描画します。
$scatter->drawScatterLegend(250, 180, array("Mode" => LEGEND_HORIZONTAL, "Style" => LEGEND_NOBORDER));

$image->render("tatechart.png");

echo "<div style='float:center; width:50%;'>
        <p><img src='tatechart.png' /></div></p>";




echo $OUTPUT->footer();

function debug_dump($getobject){
    echo "section/////////////////////////////////////////////////////////////////////////////////////////////////////////////////<br />";
    //オブジェクトクラスの中身を整理し表示する関数
    echo "<pre>";
    echo var_dump($getobject);
    echo "</pre>";
}