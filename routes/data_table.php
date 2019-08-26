<?php
require_once '../models/mysql.php';

$start_time = microtime(true);

$con = new DBMySQL('cia_db');

/****************************
 * POST資料讀取，一共四個
 ****************************/

$sid = $_POST['sid'];

/****************************
 * 計算各學院/系所人數(次)
 ****************************/

// $sql = "SELECT COUNT(*) FROM {$table_name} WHERE syear=? AND sid LIKE ?";

// $fin = array();
// $vis_data = array();
// $vis_drilldown = array();

// $data_c = array(['教育學院', 0], ['人文學院', 0], ['理學院', 0], ['管理學院', 0]);
// $data_drilldown = array([], [], [], []);
// for ($i = 0; $i < 4; $i++) {
//     for ($j = $stsyear; $j <= $edsyear; $j++) {
//         array_push($data_drilldown[$i], array("$j", 0));
//     }
// }

/**************************************
 * 回傳資料處理
 *************************************/

header('Content-Type: application/json');
echo json_encode(array('data' => 'www.google.com'));
exit;
