<?php
session_start();

// 追加ボタンを押した場合の処理
if (!empty($_POST['insert'])) {
    require_once "common/insertSql.php";

    // POSTの内容を$resipeInfoにコピー
    $recipeInfo = $_POST;

    // 日時を取得して配列に追加
    $lastUpdate = getDatestr();
    $recipeInfo['lastUpdate'] = $lastUpdate;
    // foodIdsをソート
    $foodValues = sortFoodIds($recipeInfo['foodIds']);
    $recipeInfo['foodValues'] = $foodValues;
    // print_r ($recipeInfo);

    // 追加処理
    $obj = new InsertSql('insert処理', 0);
    $result = $obj->insertRecipeT($recipeInfo);
    // print_r ($result->getResult());

    // 追加しましたよというJSのアラートがほしいな～
}

// selectでレシピ一覧をとってくる
require_once "common/SelectSql.php";

// デバッグ用
$_POST = [3, 4];
print_r ($_POST);

$test = $_POST;


require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("body_id", "recipeManagement");
$vi->setAssign("h1Title", "レシピテーブル管理画面");
$vi->setAssign("main", "recipeManagement");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
