<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once "view/View.php";


////////// 画面出力制御処理 //////////
// viewクラスの呼び出し
$vi = new View();

// viewクラスの呼び出し
$vi->setAssign("title", "ippin管理画面 | エラー");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "error");
$vi->setAssign("h1Title", "エラー");
$vi->setAssign("main", "error");

// $viに値を入れていく
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView("templateUser");


// デバッグ用※あとで消そうね！
echo '<pre>';

echo '$_SESSIONの配列';
print_r($_SESSION);
echo '<br>';

echo '</pre>';
