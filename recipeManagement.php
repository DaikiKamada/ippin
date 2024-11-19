<!-- <pre> -->
<?php
session_start();
require_once "common/Utilities.php";

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
$_POST['foodIds'] = [3, 1, 2];
$_POST['flag'] = 0;
// print_r ($_POST);

$foodIds = $_POST['foodIds'];
$flag = $_POST['flag'];

$fValueStr = sortFoodIds($foodIds);

// SelectSqlでレシピ一覧を取得
$obj = new SelectSql('レシピ一覧を取得', 0);
$result = $obj->getRecipe($fValueStr, $flag);
// print_r($result);



require_once "view/View.php";

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "recipeManagement");
$vi->setAssign("h1Title", "レシピテーブル管理画面");
$vi->setAssign("main", "recipeManagement");

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
