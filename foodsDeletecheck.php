<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once "view/View.php";

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign("title", "ippin管理画面 | 食材マスタ削除確認");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "foodsDeleteCheck");
$vi->setAssign("h1Title", "食材マスタ削除確認");
$vi->setAssign("main", "foodsDeleteCheck");

// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
// echo '<pre>';
// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']);
// echo '<br>';
// echo '</pre>';