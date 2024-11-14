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





function getFoodList(): array {
  $stt = self::getDb()->prepare('SELECT * FROM foodm ORDER BY foodid');
  $stt->execute();
  $foodAryList = $stt->fetchAll(PDO::FETCH_ASSOC);
  
  return $foodAryList;

}

}

