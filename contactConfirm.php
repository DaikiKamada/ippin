<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | お問い合わせ内容確認画面");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "contactConfirm");
$vi->setAssign("main", "contactConfirm");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
