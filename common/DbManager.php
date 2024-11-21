<?php
require_once 'ResultController.php';

// DBに接続するためのクラスです
class DbManager{

const HOST = 'localhost';
const DB_NAME = 'ippindb';
const DB_USER = 'root';
const DB_PASSWORD = 'root';

function getDb() : PDO | ResultController {
  $dsn = "mysql:host=".self::HOST.";dbname=".self::DB_NAME.";charset=utf8";	
  try {	
    $dbh = new PDO($dsn, self::DB_USER, self::DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  } catch(Exception $e){	
    return new ResultController(0, 'DB接続処理エラー', 'DB接続に失敗しました', 0);
    // exit($e->getMessage());	
  }
  return $dbh;
}

// DB接続エラーのデフォルト画面を返す
public function getDbhErrView():View {
  $vi = new View();
  $vi->setAssign("title", "ippinトップページ | エラー");
  $vi->setAssign("cssPath", "css/user.css");
  $vi->setAssign("bodyId", "error");
  $vi->setAssign("main", "error");
  $vi->setAssign("h1Title", "サービス停止中");
  $vi->setAssign("resultMsg", "いつもご利用ありがとうございます。現在サービス停止中です。時間をおいてお試しください。");
  $vi->setAssign("linkUrl", "none");
  $vi->setAssign("resultNo", "0");

  return $vi;
}


}

