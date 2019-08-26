<?php

/********************
 * MySQL資料庫連線 OOP
 ********************/

require_once 'mysql_define.php';

class DBMySQL
{
    public $_mysql_conn; //連線資料
    public $_qryRes; //儲存資料 Query回傳值
    public $_colname; // 儲存欄位名稱

    //連線 MySQL
    public function __construct($dbname)
    {
        //建立連線 並設定 UTF-8
        if (func_get_arg(1) && func_get_arg(2)) {
            $this->_mysql_conn = new PDO("mysql:host=127.0.0.1;dbname=$dbname;charset=utf8", func_get_arg(1), func_get_arg(2));
        } else if (func_get_arg(1)) {
            //只有多一個參數是錯誤的使用方式.
            return false;
        } else {
            $this->_mysql_conn = new PDO("mysql:host=127.0.0.1;dbname=$dbname;charset=utf8", DB_MYSQL_USER, DB_MYSQL_PW);
        }
        return true;
    }

    //關閉連線
    public function __destruct()
    {
        $this->_mysql_conn = false;

        return true;
    }

    //單一SQL查詢資料 (PDO::FETCH_NUM)
    public function query($sql, $arr)
    {
        //逃逸字元
        //$_sql = mysqli_real_escape_string($this->_dbConn, $sql);

        //撈資料
        $qryData = $this->_mysql_conn->prepare($sql);
        $qryData->execute($arr);

        //儲存資料後回傳
        $this->_qryRes = $qryData;

        return $qryData->fetchAll(PDO::FETCH_NUM);
    }

    // //單一SQL查詢資料 (PDO::FETCH_ASSOC) , 可一次查詢 Column 與 值.
    // public function query_ASSOC($sql, $arr)
    // {
    //     //撈資料
    //     $qryData = $this->_mysql_conn->prepare($sql);
    //     $qryData->execute($arr);

    //     //儲存資料後回傳
    //     $this->_qryRes = $qryData;

    //     return $qryData->getColumnMeta(1); //$qryData->fetchAll(PDO::FETCH_ASSOC);
    // }

    // //複數SQL查詢
    // public function queryMulti($sql)
    // {
    //     //逃逸字元
    //     //$_sql = mysqli_real_escape_string($this->_dbConn, $sql);
    //     $_sql = $sql;

    //     //撈資料
    //     if (mysqli_multi_query($this->_dbConn, $_sql)) {
    //         do {
    //             if ($_result = mysqli_store_result($this->_dbConn)) {
    //                 while ($_row = mysqli_fetch_all($_result, MYSQLI_ASSOC)) {
    //                     $this->_qryRes[] = $_row;
    //                 }
    //                 mysqli_free_result($_result);
    //             }
    //         } while (mysqli_next_result($this->_dbConn));
    //     }

    //     return $this->_qryRes;
    // }

    // //回傳查詢值（全部）
    // public function getAllData($type = null)
    // {
    //     $_term = $type;

    //     switch ($_term) {
    //         case 'MYSQLI_NUM':
    //             return mysqli_fetch_all($this->_qryRes, MYSQLI_NUM);
    //             break;

    //         case 'MYSQLI_ASSOC':
    //             return mysqli_fetch_all($this->_qryRes, MYSQLI_ASSOC);
    //             break;

    //         case 'MYSQLI_BOTH':
    //             return mysqli_fetch_all($this->_qryRes, MYSQLI_BOTH);
    //             break;

    //         default:
    //             return mysqli_fetch_all($this->_qryRes, MYSQLI_ASSOC);
    //             break;
    //     }
    // }

    // //回傳查詢值（逐筆）
    // public function getData($type = null)
    // {
    //     $_term = $type;

    //     switch ($_term) {
    //         case 'MYSQLI_NUM':
    //             return mysqli_fetch_array($this->_qryRes, MYSQLI_NUM);
    //             break;

    //         case 'MYSQLI_ASSOC':
    //             return mysqli_fetch_array($this->_qryRes, MYSQLI_ASSOC);
    //             break;

    //         case 'MYSQLI_BOTH':
    //             return mysqli_fetch_array($this->_qryRes, MYSQLI_BOTH);
    //             break;

    //         default:
    //             return mysqli_fetch_array($this->_qryRes, MYSQLI_NUM);
    //             break;
    //     }
    // }

    //回傳查詢值總數
    public function getRowCounts()
    {
        return $this->_qryRes->rowCount();
    }

    public function getColumns($tablename)
    {
        // pdo parameter 好像會把資料變成 '傳入值' , 但在 Mysql 中 資料表只能 `資料表` 不能 '資料表'
        $sql = "SHOW COLUMNS FROM $tablename WHERE Field != 'id'";

        //撈資料
        $qryData = $this->_mysql_conn->prepare($sql);
        $qryData->execute();

        //儲存資料後回傳
        $this->_qryRes = $qryData;

        return $qryData->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getSyears($tablename)
    {
        $sql = "SELECT DISTINCT syear FROM {$tablename} ORDER BY syear";

        $qryData = $this->_mysql_conn->prepare($sql);
        $qryData->execute();

        $this->_qryRes = $qryData;

        return $qryData->fetchAll(PDO::FETCH_NUM);
    }

    public function showtables()
    {
        $sql = "SHOW TABLES";
        $qryData = $this->_mysql_conn->prepare($sql);
        $qryData->execute();

        //儲存資料後回傳
        $this->_qryRes = $qryData;

        return $qryData->fetchAll(PDO::FETCH_COLUMN);
    }
}
