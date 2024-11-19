<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | 利用規約");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "tos");
$vi->setAssign("main", "tos");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
