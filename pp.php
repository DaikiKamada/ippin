<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | プライバシーポリシー");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("bodyId", "pp");
$vi->setAssign("main", "pp");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
