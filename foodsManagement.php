<?php
session_start();

require_once "view/View.php";
require_once "common/SelectSql.php";
require_once "common/insertSql.php";
require_once 'common/Utilities.php';

// insert(追加ボタンを押した場合の処理)
if(array_key_exists('insert',$_POST)) {

    // POSTの内容を$foodInfoにコピー
    $foodInfo = $_POST;
    $foodInfo['userId'] = $_SESSION['viewAry']['userId'];

    // 追加処理
    $obj = new InsertSql('食材の追加処理', 0);
    $foodsList = $obj->insertFoodM($foodInfo);

    // $_POSTを初期化
    $_POST = array();

    // 処理結果
    $result = $foodsList->getResult();
    if($result['resultNo'] == 0) {
        // 追加できなかったよというJSのアラートがほしいな～
    }
    else {
        // 追加しましたよというJSのアラートがほしいな～
    }

}
else {
    $recipeInfo = [];
}


// 食材一覧を取得
$obj = new SelectSql('食材一覧を取得', 0);
$foodsList = $obj->getFood();


$vi = new View();

$vi->setAssign("title", "ippin管理画面 | 食材マスタ管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "foodsManagement");
$vi->setAssign("h1Title", "食材マスタ管理画面");
$vi->setAssign("main", "foodsManagement");
$vi->setAssign("userId", 1);
$vi->setAssign("foodsList", $foodsList);

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
// echo '<pre>';

// echo '$foodsListの配列';
// print_r($foodsList);
// echo '$_POSTの配列';
// print_r($_POST);

// echo '</pre>';