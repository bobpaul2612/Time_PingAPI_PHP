<?php
require_once '../models/mysql.php';

$con = new DBMySQL('cia_db');

/****************************
 * POST資料讀取，一共一個
 ****************************/

$sid = $_POST['sid'];

/************************************
 * 在資料庫找尋該學生對應的臺評會帳號密碼
 ************************************/

$sql = "SELECT TP_username,TP_password FROM TP.tpdata WHERE sid = ?";

$rst = $con->query($sql, [$sid]);

/**************************************
 * call API function
 *************************************/

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

function CallAPI($url, $data = false)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}

/**************************************
 * 回傳資料處理
 *************************************/
// 代表本次輸入學號為真
if ($rst) {

    // 將使用者使用的時間紀錄進資料庫
    $dt = new DateTime("now", new DateTimeZone('Asia/Taipei'));
    $dt = $dt->format("y-m-d h:i:s");
    $sql = "UPDATE TP.tpdata SET datetime=? WHERE sid = ?";
    $con->query($sql, [$dt, $sid]);

    // // call 臺評會 API
    // // 測試環境 : http://tirc-test.twaea.org.tw/tirc/public
    // // 正式環境 : http://tirc.twaea.org.tw/public
    // $data = [
    //     'key' => 'aaJXMADF6APID6FEYTbD',
    //     'secret' => '8D5G3bKZWXVVWDQFQ3WV',
    //     'username' => $rst[0][0],
    //     'password' => $rst[0][1],
    // ];

    // $token = CallAPI('POST', 'http://tirc.twaea.org.tw/public', $data);

    // $token = 'http://http://tirc.twaea.org.tw/public/api/login-redirect?token=' . $token;

    $token = CallAPI('https://ciadw.ntcu.edu.tw/view/mtest.php', $test);

    if (!$token) {
        $token = 1;
    }
} else {
    $token = 0;
}

/**************************************
 * 回傳資料處理
 *************************************/

header('Content-Type: application/json');
echo json_encode(array('data' => $token));
exit;
