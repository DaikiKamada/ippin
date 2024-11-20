<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | エラー");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "error");
$vi->setAssign("h1Title", "エラー");
$vi->setAssign("main", "error");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
