<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'view/View.php';

// $_POSTを受ける変数の準備・初期化
$contactName = [];
$contactEmail = [];
$contactKinds = [];
$contactMessage = [];

// $_POSTの内容をそれぞれの変数に格納
$contactName = $_POST['name'];
$contactEmail = $_POST['email'];
$contactKinds = $_POST['kinds'];
$contactMessage = $_POST['message'];

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign("title", "ippin | お問い合わせ内容確認画面");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "contactConfirm");
$vi->setAssign("main", "contactConfirm");

// contact.phpから$_POSTで受け取った$contactName, $contactEmail, $contactKinds, $contactMessageを$viに渡す
$vi->setAssign("contactName",$contactName);
$vi->setAssign("contactEmail",$contactEmail);
$vi->setAssign("contactKinds",$contactKinds);
$vi->setAssign("contactMessage",$contactMessage);

// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView("templateUser");

// デバッグ用※あとで消そうね！
echo '<pre>';
echo '$_SESSIONの配列';
print_r($_SESSION['viewAry']);
print_r($_POST);
echo '</pre>';