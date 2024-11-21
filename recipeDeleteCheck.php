<?php
session_start();

require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピ削除確認画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "recipeDeleteCheck");
$vi->setAssign("h1Title", "レシピ削除確認画面");
$vi->setAssign("main", "recipeDeleteCheck");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
echo '<pre>';
// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']);
// echo '<br>';
echo '$_POSTの配列';
print_r($_POST);
echo '<br>';
echo '</pre>';