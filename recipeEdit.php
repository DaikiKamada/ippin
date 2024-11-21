<?php
session_start();

require_once 'common/UpdateSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// 仮データ
$_POST = []

print_r($_POST);

$_POST['choicedRecipe'];


$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピ編集画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "recipeEdit");
$vi->setAssign("h1Title", "レシピ編集画面");
$vi->setAssign("main", "recipeEdit");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
