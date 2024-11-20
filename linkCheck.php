<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | リンク切れチェック画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "linkCheck");
$vi->setAssign("h1Title", "リンク切れチェック画面");
$vi->setAssign("main", "linkCheck");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
