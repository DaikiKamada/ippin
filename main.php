<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'view/View.php';

// エラーコードの初期値を設定
$errorCode = 0;

// SelectSqlのインスタンスを作成
$selectSql = new SelectSql('食材の選択', 1);

// foodListを取得
$foodsList = $selectSql->getFood();

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign('title', 'ippin | トップページ');
$vi->setAssign('cssPath', 'css/user.css');
$vi->setAssign('body_id', 'main');
$vi->setAssign('main', 'main');
// 取得したfoodListを渡す
$vi->setAssign('foodsList', $foodsList);

// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi->screenView('templateUser');
