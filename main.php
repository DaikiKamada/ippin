<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | トップページ");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("body_id", "main");
$vi->setAssign("main", "main");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
