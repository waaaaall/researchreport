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
 * Prints a particular instance of researchreport
 * researchreportの特定のインスタンスを出力する。
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 * あなたはファイルの説明をもっと長くすることができますが、
 * あなたが好きなら、複数の行にまたがることができる。
 *
 * @package    mod_researchreport
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Replace researchreport with the name of your module and remove this line.

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');
require_once("$CFG->libdir/outputcomponents.php");
require_once("$CFG->libdir/navigationlib.php");
require_once('./rr_form.php');

//pChart sample
include($CFG->dirroot.'/mod/researchreport/pChart/class/pData.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pDraw.class.php');
include($CFG->dirroot.'/mod/researchreport/pChart/class/pImage.class.php');

// $userid = optional_param('userid', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT); // Course Module ID
$r  = optional_param('n', 0, PARAM_INT);  // ResearchReport ID
$userid   = optional_param('userid', null, PARAM_INT);


if ($id) {
    $cm         = get_coursemodule_from_id('researchreport', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $researchreport  = $DB->get_record('researchreport', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($r) {
    $researchreport  = $DB->get_record('researchreport', array('id' => $r), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $researchreport->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('researchreport', $researchreport->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);

// Conditions to show the intro can change to look for own settings or whatever.
if ($researchreport->intro) {
    echo $OUTPUT->box(format_module_intro('researchreport', $researchreport, $cm->id), 'generalbox mod_introbox', 'researchreportintro');
}

$event = \mod_researchreport\event\course_module_viewed::create(array(
    'objectid' => $PAGE->cm->instance,
    'context' => $PAGE->context,
));
$event->add_record_snapshot('course', $PAGE->course);
$event->add_record_snapshot($PAGE->cm->modname, $researchreport);
$event->trigger();

// Print the page header.
//$PAGE->set_pagelayout('standard');

$PAGE->set_url('/mod/researchreport/view.php', array(
        'id' =>$cm->id
        ));
//$PAGE->navbar->add('navbarsample', new moodle_url('/mod/researchreport/view.php', array('id' =>$cm->id));
$PAGE->set_title(format_string($researchreport->name));
$PAGE->set_heading(format_string($course->fullname));


/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('researchreport-'.$somevar);
 */

// Output starts here.ページに表示するのはここから
echo $OUTPUT->header();

// Replace the following lines with you own code.
//ここからmain記述
echo $OUTPUT->heading('research report');


//研究進捗フォームへのリンク
// $addurl = new moodle_url("$CFG->wwwroot/mod/researchreport/progress_form.php");
// $addlink = '<div class="addentrylink">';
// $addlink .= '<a href="'.$addurl->out().'">'. '研究進捗を記録する'.'</a>';
// $addlink .= '<a href="'.$addurl->out().'">'.'</a>';
// $addlink .= '</div>';
// echo $addlink;

//mixsample
// echo "<div style='float:center; width:50%;'>
//         <p><img src='mixchartsample.png' /></div></p>";

//研究活動  フォームへのリンク
$addurl = new moodle_url("$CFG->wwwroot/mod/researchreport/activity_form.php", array(
    'userid' =>$USER->id
));
$addlink = '<p><a href="'.$addurl->out().'">'. '個人活動を記録する'.'</a></p>';
echo $addlink;

//全ての活動へのリンク
$addurl = new moodle_url("$CFG->wwwroot/mod/researchreport/allactivity.php");
$addlink = '<p><a href="'.$addurl->out().'">'. '全ての活動'.'</a></p>';
echo $addlink;

$addurl = new moodle_url("$CFG->wwwroot/mod/researchreport/personal_activity.php", array(
    'userid' =>$USER->id
));
$addlink = '<p><a href="'.$addurl->out().'">'. '個人の記録'.'</a></p>';

echo $addlink;
    
//useridの最大値を取得
$maxuserid = $DB->get_records_sql("SELECT max(userid) FROM mdl_rrpost ");
$maxuserid = key($maxuserid);
    
//昇順比較
$alluserid = $DB->get_records_sql("SELECT DISTINCT userid FROM mdl_rrpost");
//     debug_dump($alluserid);
$alluserid = array_keys($alluserid);
//     debug_dump($alluserid);
foreach($alluserid as $key_1 => $val_1){
    $getdata = $DB->get_records_sql("SELECT * FROM mdl_rrpost WHERE userid = $val_1");
    $getdata = array_merge($getdata,array());
    foreach($getdata as $key_2 => $val_2);
    $cmpdata[$key_1]->date = $getdata[$key_2]->created;
    $cmpdata[$key_1]->userid = $getdata[$key_2]->userid;
    }
    
    foreach($cmpdata as $key =>$val){
            $sort[$key] = $val->date;
    }
    array_merge($sort);
    array_multisort($sort, SORT_DESC, $cmpdata);
//     debug_dump($cmpdata);
//     debug_dump($sort);
//     echo 'ここまで';
    
    //チャート生成のカウンタ
    $pcount=0;
    
    foreach($cmpdata as $key => $val){
//         echo $key,':1st<br>';
        $userid = $cmpdata[$key]->userid;
        $getdata = $DB->get_records_sql("SELECT * FROM mdl_rrpost WHERE userid = $userid");
        $getdata = array_merge($getdata,array());
        
        $username = $DB->get_records_sql("SELECT username FROM mdl_user WHERE id = $userid");
        $username = key($username);
            
        $personalurl = new moodle_url("$CFG->wwwroot/mod/researchreport/personal_activity.php", array(
             'userid' => $userid
        ));
        $personallink =  '<a href="'.$personalurl->out().'">'. $username.'</a>';
 
        //getdataから必要なデータだけ取り出す
        $encodedata = new stdClass();
        $encodedata->userid = array();
        $encodedata->acttime = array();
        $encodedata->studytime = array();
        $encodedata->evalution = array();
        $encodedata->timemd = array();
    //     debug_dump($encodedata);
        
            //最新7日間の値だけ取得する
//             foreach($getdata as $key => $val){
        foreach($getdata as $key => $val){
//             echo $key,':2nd<br>';
//                 echo $key;
                array_push($encodedata->userid,$getdata[$key]->userid);
                array_push($encodedata->acttime,$getdata[$key]->acttime);
                array_push($encodedata->studytime,$getdata[$key]->studytime);
                array_push($encodedata->evalution,$getdata[$key]->evalution);
                array_push($encodedata->timemd,$getdata[$key]->timemd);
            }
//             debug_dump($encodedata);

            //最新の活動内容と更新時間の取得
            $newestp = new stdClass();
            $newestp->date = $getdata[$key]->created;
            $newestp->post = $getdata[$key]->summary;
            $newestp->task = $getdata[$key-1]->task;
//             debug_dump($newestp);
            
            //最新１週間分を取得
            $pdrawdata = new stdClass();
            $pdrawdata->userid = array();
            $pdrawdata->acttime = array();
            $pdrawdata->studytime = array();
            $pdrawdata->sumtime = array();
            $pdrawdata->evalution = array();
            $pdrawdata->timemd = array();
            
            for($i=6;$i>=0;$i--){
                array_push($pdrawdata->userid,$encodedata->userid[$key-$i]);
                array_push($pdrawdata->acttime,$encodedata->acttime[$key-$i]);
                array_push($pdrawdata->studytime,$encodedata->studytime[$key-$i]);
                array_push($pdrawdata->sumtime,$encodedata->acttime[$key-$i] + $encodedata->studytime[$key-$i]);
                array_push($pdrawdata->evalution,$encodedata->evalution[$key-$i]);
                array_push($pdrawdata->timemd,$encodedata->timemd[$key-$i]);
            }
            
                //setting_activitytime_chart
                $myData = new pData();
                $myData->addPoints($pdrawdata->acttime,"Serie1");
//                 $myData->setPalette("Serie1", array("R"=>51,"G"=>153,"B"=>150));
                $myData->setSerieDescription("Serie1",activity);
                $myData->setSerieOnAxis("Serie1",0);
                
                $myData->addPoints($pdrawdata->studytime,"Serie2");
                //                 $myData->setPalette("Serie1", array("R"=>51,"G"=>153,"B"=>150));
                $myData->setSerieDescription("Serie2",study);
                $myData->setSerieOnAxis("Serie2",0);
            
                $myData->addPoints($pdrawdata->timemd,"Absissa");
                $myData->setAbscissa("Absissa");
                $myData->setAbscissaName("Date");
            
                $myData->setAxisPosition(0,AXIS_POSITION_LEFT);
                $myData->setAxisName(0,"ActTime");
                $myData->setAxisUnit(0,"");
                
                $myPicture = new pImage(300,250,$myData);
                $myPicture->Antialias = FALSE;
                $myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
                $TextSettings = array("Align"=>TEXT_ALIGN_TOPLEFT
                    , "R"=>0, "G"=>0, "B"=>0);
                $myPicture->drawText(50,10,"Activity",$TextSettings);
            
                $myPicture->setGraphArea(50,50,275,210);
                $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>10));
            
                $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
                    , "Mode"=>SCALE_MODE_MANUAL
                    , "ManualScale"=>array(
                        0=>array("Min"=>0,"Max"=>12),
//                         1=>array("Min"=>0,"Max"=>100)
                    )
                    , "LabelingMethod"=>LABELING_ALL
                    , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "DrawArrows"=>1, "DrawXLines"=>0, "DrawYLines"=>NONE);
                $myPicture->drawScale($Settings);
            
                $Config = array("DisplayValues"=>0, "AroundZero"=>0);
                
//                 $myPicture->drawBarChart($Config);
                
                $myPicture->drawStackedBarChart($Config);
            
                $Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/pf_arma_five.ttf", "FontSize"=>12, "Margin"=>0, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
                    , "Mode"=>LEGEND_HORIZONTAL);
                $myPicture->drawLegend(180,16,$Config);
            
                $myPicture->render("$pcount.myActivity.png");
                
                
                ///////////////////////////////////////////////////////////////////////////////////////////////
                //setting_Progeress_chart
                $myData = new pData();
                $myData->addPoints($pdrawdata->evalution,"Serie1");
//                 $myData->setPalette("Serie1",array("R"=>155,"G"=>155,"B"=>155));
                $myData->setSerieDescription("Serie1","evalution");
                $myData->setSerieOnAxis("Serie1",0);
                
                $myData->addPoints($pdrawdata->timemd,"Absissa");
                $myData->setAbscissa("Absissa");
                $myData->setAbscissaName("Date");
                
                $myData->setAxisPosition(0,AXIS_POSITION_LEFT);
                $myData->setAxisName(0,"Progress");
                $myData->setAxisUnit(0,"");
                
                $myPicture = new pImage(300,250,$myData);
                $myPicture->Antialias = FALSE;
                $myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
                $TextSettings = array("Align"=>TEXT_ALIGN_TOPLEFT
                    , "R"=>0, "G"=>0, "B"=>0);
                $myPicture->drawText(50,10,"Progress",$TextSettings);
                
                $myPicture->setGraphArea(50,50,275,210);
                $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>10));
                
                $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
                    , "Mode"=>SCALE_MODE_MANUAL
                    , "ManualScale"=>array(0=>array("Min"=>0,"Max"=>100))
                    , "LabelingMethod"=>LABELING_ALL
                    , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "DrawArrows"=>1, "DrawXLines"=>0, "DrawYLines"=>NONE);
                $myPicture->drawScale($Settings);
                
                $Config = array("DisplayValues"=>0,  "AroundZero"=>1);
                $myPicture->drawAreaChart($Config);
                
                $Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/pf_arma_five.ttf", "FontSize"=>12, "Margin"=>0, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
                    , "Mode"=>LEGEND_HORIZONTAL
                );
                $myPicture->drawLegend(200,16,$Config);
                
                $myPicture->render("$pcount.myProgress.png");
                
                ////////////////////////////////////////////////////////////////
                //mix
                $myData = new pData();
                $myData->addPoints($pdrawdata->acttime,"Serie1");
                //                 $myData->setPalette("Serie1", array("R"=>51,"G"=>153,"B"=>150));
                $myData->setSerieDescription("Serie1",activitytime);
                $myData->setSerieOnAxis("Serie1",0);
                
                $myData->addPoints($pdrawdata->evalution,"Serie2");
                $myData->setSerieDescription("Serie2","evalution");
                $myData->setPalette("Serie2", array("R"=>51,"G"=>153,"B"=>150));
                $myData->setSerieOnAxis("Serie2",1);
                
                $myData->addPoints($pdrawdata->timemd,"Absissa");
                $myData->setAbscissa("Absissa");
                $myData->setAbscissaName("Date");
                
                $myData->setAxisPosition(0,AXIS_POSITION_LEFT);
                $myData->setAxisName(0,"ActTime");
                $myData->setAxisUnit(0,"");
                
                $myData->setAxisPosition(1,AXIS_POSITION_RIGHT);
                $myData->setAxisName(1,"test");
                $myData->setAxisUnit(1,"");
                
                $myPicture = new pImage(300,250,$myData);
                $myPicture->Antialias = FALSE;
                $myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
                $TextSettings = array("Align"=>TEXT_ALIGN_TOPLEFT
                    , "R"=>0, "G"=>0, "B"=>0);
                $myPicture->drawText(50,10,"Activity",$TextSettings);
                
                $myPicture->setGraphArea(50,50,275,210);
                $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>10));
                
                $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
                    , "Mode"=>SCALE_MODE_MANUAL
                    , "ManualScale"=>array(
                        0=>array("Min"=>0,"Max"=>12),
                                                1=>array("Min"=>0,"Max"=>100)
                    )
                    , "LabelingMethod"=>LABELING_ALL
                    , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>0, "DrawYLines"=>ALL);
                $myPicture->drawScale($Settings);
                
                $Config = array("DisplayValues"=>1, "AroundZero"=>1);
                
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
                
                $myPicture->render("mixchartsample.png");
                
                
                
                
                ////////////////////////////////////////////////////////////////
                //描画
                if($pcount%2 == 0){
                        echo 
                   "<div style='
                        float:left; width:48%;word-wrap: break-word;
                            padding: 0.5em 1em;
                            margin: 0.5em 0;
                            background: #f4f4f4;
                            border-left: solid 6px #f4f1a6;
                            box-shadow: 0px 2px 3px rgba(0, 0, 0, 0,0)''>
                        <p>ユーザー：$personallink</p>
        
                        <div style='float:left; width:50%;'>
                            <p><img src='$pcount.myActivity.png'/></div></p>
                        <div style='float:left; width:50%;'>
                            <p><img src='$pcount.myProgress.png' /></div></p>
            
                        <div style='
                            float:left; width:100%;
                            word-wrap: break-word;
                            padding: 0em 1em;
                            margin: 0.5em 0;
                            background: #f4f4f4;
                            border-left: solid 6px #5bb7ae;;
                            box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.0)'>
                            <p>$newestp->date</p>
                            <p>課題:<br>$newestp->task</p>
                            <p>活動:<br>$newestp->post</p>
        
                        </div>
                    </div>";
                        $pcount++;
                }
                else{
                    echo 
                   "<div style='
                        float:right; width:48%; word-wrap: break-word;
                            padding: 0.5em 1em;
                            margin: 0.5em 0;
                            background: #f4f4f4;
                            border-left: solid 6px #f4f1a6;
                            box-shadow: 0px 2px 3px rgba(0, 0, 0, 0,0)''>
                        <p>ユーザー：$personallink</p>
                        <div style='float:left; width:50%;'>
                            <p><img src='$pcount.myActivity.png'/></div></p>
                        <div style='float:left; width:50%;'>
                            <p><img src='$pcount.myProgress.png' /></div></p>
            
                        <div style='
                            float:left; width:100%;
                            word-wrap: break-word;
                            padding: 0em 1em;
                            margin: 0.5em 0;
                            background: #f4f4f4;
                            border-left: solid 6px #5bb7ae;
                            box-shadow: 0px 2px 3px rgba(0, 0, 0, 0,0)'>
                            <p>$newestp->date</p>
                            <p>課題:<br>$newestp->task</p>
                            <p>活動:<br>$newestp->post</p>
                            
                        
                        </div>
                    </div>";
                        $pcount++;
                }
//                 echo $key,':3rd<br>';
        } 
//   }
//   debug_dump($cmpdata);
//     $sort = array();
// //     $i=0;
//   foreach($cmpdata as $key =>$val){
//       array_push($sort, $val->date);
// //       $sort[] = $val[date];
// //       $i++;
//   }
//        echo $cmpdata->$key[date];
//        debug_dump($sort);
//        array_merge($sort);
       
// //        debug_dump($cmpdata);
      
//        array_multisort($sort, SORT_DESC, $cmpdata);
// //        array_multisort($cmpdata->date, SORT_DESC);
//         debug_dump($cmpdata);
//        debug_dump($sort);
       
//        $pcount=0;
//        foreach($cmpdata as $key => $val){
  
//                     if($pcount%2 == 0){
//                             echo
//                        "<div style='
//                             float:left; width:48%;'>
//                             <p>ユーザー：$USER->username</p>
    
//                             <div style='float:left; width:50%;'>
//                                 <p><img src='$.myActivity.png'/></div></p>
//                             <div style='float:left; width:50%;'>
//                                 <p><img src='$key.myProgress.png' /></div></p>
    
//                             <div style='
//                                 float:left; width:100%;
//                                 word-wrap: break-word;
//                                 padding: 0.5em 1em;
//                                 margin: 1em 0;
//                                 background: #f4f4f4;
//                                 border-left: solid 6px #5bb7ae;
//                                 box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.33)'>
//                                 <p>更新日：</p>
//                                 <p>$cmptime->summary[$key]</p>
    
//                             </div>
//                         </div>";
//                             $pcount++;
//                     }
//                     else{
//                         echo
//                        "<div style='
//                             float:right; width:48%;'>
//                             <p>ユーザー：$username</p>
//                             <div style='float:left; width:50%;'>
//                                 <p><img src='$key.myActivity.png'/></div></p>
//                             <div style='float:left; width:50%;'>
//                                 <p><img src='$key.myProgress.png' /></div></p>
    
//                             <div style='
//                                 float:left; width:100%;
//                                 word-wrap: break-word;
//                                 padding: 0.5em 1em;
//                                 margin: 1em 0;
//                                 background: #f4f4f4;
//                                 border-left: solid 6px #5bb7ae;
//                                 box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.33)'>
//                                 <p>aa</p>
//                                 <p>aa</p>
    
//                             </div>
//                         </div>";
//                             $pcount++;
//                     }
//        }


//新しいエントリエントリ一覧10/3コメントアウト
//$bloglisting->print_entries();

// Finish the page.フッター表示
//         echo '今は何も表示してません。エラーではないので大丈夫です';
echo $OUTPUT->footer();

function debug_dump($getobject){
    echo "section<br />";
    //オブジェクトクラスの中身を整理し表示する関数
    echo "<pre>";
    echo var_dump($getobject);
    echo "</pre>";
}
