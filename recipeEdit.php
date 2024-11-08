<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピ編集画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "recipeEdit");
$vi->setAssign("h1Title", "レシピ編集画面");
$vi->setAssign("main", "recipeEdit");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
