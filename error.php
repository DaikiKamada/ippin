<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once "view/View.php";


////////// 画面出力制御処理 //////////
// SelectSqlでリンク切れしたレシピ一覧を取得
$vi = new View();

// viewクラスの呼び出し
$vi->setAssign("title", "ippin管理画面 | エラー");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "error");
$vi->setAssign("h1Title", "エラー");
$vi->setAssign("main", "error");

// $viに値を入れていく
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView("templateAdmin");
