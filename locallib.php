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
 * Internal library of functions for module researchreport
 * researchreport用の関数の内部ライブラリ
 *
 * All the researchreport specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 * モジュールロジックを実装するために必要な
 * 全てのresearchreport固有の関数はここになければなりません。
 * lib.phpからこのファイルを絶対に含めないでください。
 *
 * @package    mod_researchreport
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

class rr_entry implements renderable {
    public $id;
    public $userid;
    public $courseid;
    public $groupid;
    public $subject;
    public $entry;
    public $acttime;
    public $evaluation;
    public $created;
    public $lastmodified;
    
public function add() {
    global $CFG,$USER,$DB;
    
    unset($this->id);
    $this->module       = 'researchreport';
    $this->userid       = (empty($this->userid)) ? $USER->id : $this->userid;
    $this->lastmodified = time();
    $this->created      = time();
    
    //データベースに入力した値を挿入
    $this->id = $DB->insert_record('rrpost', $this);
    
}

public function __construct($id=null) {
    global $DB, $PAGE, $CFG;
}

}

/*
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 *function researchreport_do_something_useful(array $things) {
 *    return new stdClass();
 *}
 */
