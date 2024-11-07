<?php
// セッションの開始
session_start();


// トップページで処理したい場合はここに書く


//viewの呼び出し
require_once "view/View.php";

$vi = new View();

// ページタイトル
$vi->setAssign("title", "トップページ");
// cssの呼び出し
$vi->setAssign("cssPath", "css/user.css");
// body_idの設定
$vi->setAssign("body_id", "index");
// htmlのbodyの呼び出し
$vi->setAssign("main", "main");

$_SESSION['viewAry'] = $vi->getAssign();
// テンプレートの呼び出し
$vi ->screenView("template");
