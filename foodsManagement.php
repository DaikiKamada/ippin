<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | 食材マスタ管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "foodsManagement");
$vi->setAssign("h1Title", "食材マスタ管理画面");
$vi->setAssign("main", "foodsManagement");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
