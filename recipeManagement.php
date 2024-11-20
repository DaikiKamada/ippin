<!-- <pre> -->
<?php
session_start();

// ファイルのインクルード
require_once "common/SelectSql.php";
require_once "common/insertSql.php";
require_once 'common/Utilities.php';
require_once 'view/View.php';
// print_r($_SESSION['result']);


// 追加ボタンを押した場合の処理
if (!empty($_POST['insert'])) {

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

// デバッグ用
$_POST['foodIds'] = [4, 5];
$_POST['flag'] = 0;
// print_r ($_POST);

$foodIds = $_POST['foodIds'];
$flag = $_POST['flag'];

$fValueStr = sortFoodIds($foodIds);

// SelectSqlでレシピ一覧を取得
$obj = new SelectSql('レシピ一覧を取得', 0);
$result = $obj->getRecipe($fValueStr, $flag);
// print_r($result);

// foreach($result as $x) {
//     $foodIds[$x] = explodeFoodValues($result[$x]['foodValues']);
// }

for($i = 0; $i < count($result); $i++) {
    $foodIds[$i] = explodeFoodValues($result[$i]['foodValues']);
}



$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "recipeManagement");
$vi->setAssign("h1Title", "レシピテーブル管理画面");
$vi->setAssign("main", "recipeManagement");
$vi->setAssign("result", $result);

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
echo '<pre>';
echo '$_SESSIONの配列';
print_r($_SESSION['result']);
echo '$_SESSIONの配列';
print_r($_SESSION);
echo '$resultの配列';
print_r($result);
echo '$foodIdsの配列';
print_r($foodIds);

echo '</pre>';