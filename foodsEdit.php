<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | 食材マスタ編集画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "foodsEdit");
$vi->setAssign("main", "foodsEdit");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
