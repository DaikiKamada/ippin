<?php
session_start();

require_once 'common/UpdateSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// 仮データ
$_POST = [];

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

// デバッグ用※あとで消そうね！
echo '<pre>';

// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']['recipeList']);
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '$resultの配列';
// print_r($result);
// echo '$foodIdsの配列';
// print_r($foodIds);
// echo '$recipeInfoの配列';
// print_r($recipeInfo);
echo '$_POSTの配列';
print_r($_POST);
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '$recipeListの配列';
// print_r($recipeList);

echo '</pre>';