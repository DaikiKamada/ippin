<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'view/View.php';

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign('title', 'ippin | 利用規約');
$vi->setAssign('cssPath', 'css/user.css');
$vi->setAssign('bodyId', 'tos');
$vi->setAssign('main', 'tos');

// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView('templateUser');
