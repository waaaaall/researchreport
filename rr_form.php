<?php
use block_lp\output\summary;

//moodleform is defined in formslib.php

require_once("$CFG->libdir/formslib.php");
require_once("$CFG->libdir/outputcomponents.php");

//moodleform is defined in formslib.php
require_once("$CFG->libdir/formslib.php");

class test_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB;
           
        $mform =& $this->_form; // Don't forget the underscore!
        
        //エントリエディットフォーム
        //活動内容
        //$mform->addElement('header', 'body', get_string('body', 'researchreport'));
        $mform->addElement('header', 'body', '活動内容');
        
       //研究内容のエディター作成
//         $mform->addElement('editor', 'entry', get_string('body', 'researchreport'), null, $entryoptions);
        $mform->addElement('editor', 'entry', '活動内容を入力');
        $mform->setType('entry', PARAM_RAW);
        
        //デフォルトでエディターに表示させるテキスト
        $mform->setDefault('entry', array('text'=>'研究内容エディタ'));
        
        //ファイルピッカー
        //$mform->addElement('filemanager', 'attachment_filemanager', get_string('attachment', 'forum'), null, $attachmentoptions);
        
        //達成度の配列
        $scorearray = array(
            'val1' => '100',
            'val2' => '80',
            'val3' => '60',
            'val4' => '40',
            'val5' => '20',
            'val6' => '0'
        );
        
        //達成度セレクタ作成
        $mform->addElement('header', 'score', '評価');
        $mform->addElement('select','score_select', '評価',$scorearray);
        
        
        $timearray = array(
            'val_1' => '0.5',
            'val_2' => '1',
            'val_3' => '1.5',
            'val_4' => '2',
            'val_5' => '2.5',
            'val_6' => '3',
            'val_7' => '3.5',
            'val_8' => '4',
            'val_9' => '4.5',
            'val_10' => '5',
            'val_11' => '5.5',
            'val_12' => '6',
            'val_13' => '6.5',
            'val_14' => '7',
            'val_15' => '7.5',
            'val_16' => '8',
            'val_17' => '8.5',
            'val_18' => '9',
            'val_19' => '9.5',
            'val_20' => '10',
            'val_21' => '10.5',
            'val_22' => '11',
            'val_23' => '11.5',
            'val_24' => '12',
            
        );
        //研究時間
        $mform->addElement('header', 'time', '時間');
        $mform->addElement('select', 'time_select', '研究時間を選択', $timearray);
        
        //講義時間
        $mform->addElement('header', 'time', '時間');
        $mform->addElement('select', 'time_select', '研究時間を選択', $timearray);
        
        //送信ボタン
        $this->add_action_buttons();
    }
    
    //Custom validation should be added here
    function validation($data, $files) {
        global $CFG, $DB, $USER;
        
        return array();
    }
}

class progress_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB;
        
        $mform =& $this->_form; // Don't forget the underscore!
        
        //エントリエディットフォーム
        //研究内容の作成
        $mform->addElement('header', 'body', '課題');
        
        //研究内容のエディター作成
        $mform->addElement('editor', 'progress_task', '課題の入力');
        $mform->setType('progress_task', PARAM_RAW);
        
        //デフォルトでエディターに表示させるテキスト
        $mform->setDefault('progress_task', array('text'=>'section'));
        
        //ファイルピッカー
        //$mform->addElement('filemanager', 'attachment_filemanager', get_string('attachment', 'forum'), null, $attachmentoptions);
        
        //達成度の配列
        $scorearray = array(
            'val1' => '100',
            'val2' => '80',
            'val3' => '60',
            'val4' => '40',
            'val5' => '20',
            'val6' => '0'
        );
        //達成度セレクタ作成
        $mform->addElement('header', 'score', '評価');
        $mform->addElement('select','score_select', '評価', $scorearray);

        //送信ボタン
        $this->add_action_buttons();
    }
    
    //Custom validation should be added here
    function validation($data, $files) {
        global $CFG, $DB, $USER;
        
        return array();
    }
}

class activity_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG,$DB;
        
        $mform =& $this->_form; // Don't forget the underscore!
       
        //エントリヘッダ
        $mform->addElement('header', 'body', '活動');
        //エントリエディタ
        $mform->addElement('editor', 'summary', '今日の活動を入力して下さい');
        $mform->setType('summary', PARAM_RAW);
        $mform->addRule('summary', '必須','required', null);
        //デフォルトでエディターに表示させるテキスト
        $mform->setDefault('summary', array('text'=>''));
        
        
        $mform->addElement('header', 'body', '課題');
        //エントリエディタ
        $mform->addElement('editor', 'task', '次にやる予定の課題を入力して下さい');
        $mform->setType('task', PARAM_RAW);
        $mform->addRule('task', '必須','required', null);
//         $mform->addHelpButton('task', 'あいうえお','ここに入力');
        //デフォルトでエディターに表示させるテキスト
        $testmessage = $USER->username;
        echo $testmessage;
        $mform->setDefault('task', array('text'=>''));
        
       
//         //タスクヘッダー
//         $mform->addElement('header', 'body', '評価');
//         //タスクエディタ
//         $mform->addElement('editor', 'evalution', '評価の入力');
//         $mform->setType('entry', PARAM_RAW);
//         $mform->setDefault('evalutionk', array('text'=>'デフォルト評価'));
        
        //タイムヘッダ
        $mform->addElement('header', 'body', '活動時間');
        //活動開始終了時間用配列
        $timearray = array(
            '0000'=>'0:00',
            '0100'=>'1:00',
            '0200'=>'2:00',
            '0300'=>'3:00',
            '0400'=>'4:00',
            '0500'=>'5:00',
            '0600'=>'6:00',
            '0700'=>'7:00',
            '0800'=>'8:00',
            '0900'=>'9:00',
            '1000'=>'10:00',
            '1100'=>'11:00',
            '1200'=>'12:00',
            '1300'=>'13:00',
            '1400'=>'14:00',
            '1500'=>'15:00',
            '1600'=>'16:00',
            '1700'=>'17:00',
            '1800'=>'18:00',
            '1900'=>'19:00',
            '2000'=>'20:00',
            '2100'=>'21:00',
            '2200'=>'22:00',
            '2300'=>'23:00',
            '2400'=>'24:00',
        );
        //活動開始時間セレクタ
        $select = $mform->addElement('select','sttime', '開始時間', $timearray);
        $select->setSelected('0900');
        $mform->addRule('sttime', '必須','required', null);
        //活動開始時間セレクタ
        $select = $mform->addElement('select','edtime', '終了時間', $timearray);
        $select->setSelected('1800');
        $mform->addRule('edtime', '必須','required', null);
        
        //研究時間用配列
        $acttimearray = array(
            '0'=>'0',
            '1'=>'1',
            '2'=>'2',
            '3'=>'3',
            '4'=>'4',
            '5'=>'5',
            '6'=>'6',
            '7'=>'7',
            '8'=>'8',
            '9'=>'9',
            '10'=>'10',
            '11'=>'11',
            '12'=>'12');
        
        //タイムセレクタ
        $select = $mform->addElement('select','acttime', '研究時間', $acttimearray);
//         $select->setSelected('5');
        $mform->addRule('acttime', '必須','required', null);
        $select = $mform->addElement('select','studytime', '勉強時間', $acttimearray);
//         $select->setSelected('3');
        $mform->addRule('studytime', '必須','required', null);
        
        //スコア配列
        $scorearray = array(
            '10'=>'10',
            '20'=>'20',
            '30'=>'30',
            '40'=>'40',
            '50'=>'50',
            '60'=>'60',
            '70'=>'70',
            '80'=>'80',
            '90'=>'90',
            '100'=>'100' );
        //スコアヘッダ
        $mform->addElement('header', 'body', '評価');
        //スコアセレクタ
        $select = $mform->addElement('select','evalution', '評価', $scorearray);
        $select->setSelected('50');
        $mform->addRule('evalution', '必須','required', null);
//         //スコアヘッダ
//         $mform->addElement('header', 'body', '評価');
//         //スコアセレクタ
//         $mform->addElement('select','task', '評価', $scorearray);
        
        
        //送信ボタン
        $this->add_action_buttons();
    }
    
    //Custom validation should be added here
    function validation($data, $files) {
        global $CFG, $DB, $USER;
        
        return array();
    }
}
