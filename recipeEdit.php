<?php
session_start();

require_once 'common/UpdateSql.php';
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// 仮データ
// $_POST = [];

print_r($_POST);

$recipeIds = $_POST['choicedRecipe'];
$recipeInfo = $_SESSION['viewAry']['recipeList'];

// 全ての食材を取得
$selectFoods = new SelectSql('全ての食材を取得', 0);
$allFoodsList = $selectFoods->getFood();

// 配列を用意(編集したいレシピを入れる)
$editedRecipe = [];

// 編集したいレシピの一覧を取得
for($i = 0; $i < count($recipeInfo); $i++) {
    for($x = 0; $x < count($recipeIds); $x++) {
        if($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
            $editedRecipe[] = $recipeInfo[$i];
        }
    }
}

// 編集ボタンが押されたら、recipteTableを更新してrecipeManagementに戻る
if(array_key_exists('update', $_POST)) {
    $updateRecipe = new UpdateSql('レシピを更新', 0);

    // 複数のレコードを更新する
    $result = $updateRecipe->updateRecipeT($editedRecipe);

    // 処理結果に応じての処理ができたらいいな～
}

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピ編集画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "recipeEdit");
$vi->setAssign("h1Title", "レシピ編集画面");
$vi->setAssign("main", "recipeEdit");
$vi->setAssign("editedRecipe", $editedRecipe);
$vi->setAssign("allFoodsList", $allFoodsList);

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
// echo '<pre>';
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
// echo '$_POSTの配列';
// print_r($_POST);
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '$recipeListの配列';
// print_r($recipeList);
// echo '</pre>';