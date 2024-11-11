<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | 食材マスタ削除確認");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "foodsDeleteCheck");
$vi->setAssign("h1Title", "食材マスタ削除確認");
$vi->setAssign("main", "foodsDeleteCheck");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
