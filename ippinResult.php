<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin | 作れるippinの検索結果");
$vi->setAssign("cssPath", "css/user.css");
$vi->setAssign("body_id", "ippinResult");
$vi->setAssign("main", "ippinResult");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateUser");
