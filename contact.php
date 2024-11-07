<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | お問い合わせ");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("body_id", "contact");
$vi->setAssign("main", "contact");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
