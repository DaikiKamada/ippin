<?php

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'view/View.php';

// セッションの開始
session_start();

// エラーコードの初期値を設定
$errorCode = 0;

// SelectSqlのインスタンスを作成
$selectSql = new SelectSql('食材の選択', 1);

// foodListを取得
$foodsList = $selectSql->getFood();

// viewの制御
$vi = new View();

$vi->setAssign('title', 'ippin | トップページ');
$vi->setAssign('cssPath', 'css/user.css');
$vi->setAssign('body_id', 'main');
$vi->setAssign('main', 'main');
// 取得したfoodListを渡す
$vi->setAssign('foodsList', $foodsList);

// viewを表示
$vi->screenView('templateUser');
