<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'view/View.php';

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign("title", "ippin | お問い合わせ");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "contact");
$vi->setAssign("main", "contact");

if(isset($_SESSION['viewAry']['contactName'])) {
    $vi->setAssign("contactName",$_SESSION['viewAry']['contactName']);
}


// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView("templateUser");

// デバッグ用※あとで消そうね！
echo '<pre>';
print_r($_SESSION['viewAry']);
echo '</pre>';