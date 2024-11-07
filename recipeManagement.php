<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "recipeManagement");
$vi->setAssign("main", "recipeManagement");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
