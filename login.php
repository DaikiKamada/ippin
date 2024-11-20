<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | ログイン");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "login");
$vi->setAssign("main", "login");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
