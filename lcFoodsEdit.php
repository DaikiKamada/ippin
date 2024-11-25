<?php

// セッションの開始
session_start();

// 処理処理

//viewの呼び出し
require_once 'view/View.php';

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign('title', 'トップページ');
$vi->setAssign('cssPath', 'css/user.css');
$vi->setAssign('bodyId', 'index');
$vi->setAssign('main', 'main');

// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// テンプレートの呼び出し
$vi ->screenView('template');
