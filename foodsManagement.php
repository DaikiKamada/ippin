<?php
session_start();

require_once "view/View.php";
require_once "common/SelectSql.php";
require_once "common/insertSql.php";
require_once 'common/Utilities.php';

// insert(追加ボタンを押した場合の処理)
// if() {

// }


// 食材一覧を取得
$obj = new SelectSql('食材一覧を取得', 0);
$foodsList = $obj->getFood();



$vi = new View();

$vi->setAssign("title", "ippin管理画面 | 食材マスタ管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "foodsManagement");
$vi->setAssign("h1Title", "食材マスタ管理画面");
$vi->setAssign("main", "foodsManagement");
$vi->setAssign("foodsList", $foodsList);

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
echo '<pre>';

// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']['recipeList']);
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '$resultの配列';
// print_r($result);
echo '$foodsListの配列';
print_r($foodsList);

echo '</pre>';