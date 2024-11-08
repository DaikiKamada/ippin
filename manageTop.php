<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | 管理者トップ画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "manageTop");
$vi->setAssign("h1Title", "管理者トップ画面");
$vi->setAssign("main", "manageTop");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
