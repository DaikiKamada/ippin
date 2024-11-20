<!-- <pre> -->
<?php
session_start();

// ファイルのインクルード
require_once "common/SelectSql.php";
require_once "common/insertSql.php";
require_once 'common/Utilities.php';
require_once 'view/View.php';

// insert(追加ボタンを押した場合の処理)
if (array_key_exists('insert',$_POST)) {

    // POSTの内容を$resipeInfoにコピー
    $recipeInfo = $_POST;
    $recipeInfo['foodIds'] = $_SESSION['viewAry']['foodIds'];
    $recipeInfo['userId'] = $_SESSION['viewAry']['userId'];

    // 日時を取得して配列に追加
    $lastUpdate = getDatestr();
    $recipeInfo['lastUpdate'] = $lastUpdate;
    // foodIdsをソート
    $foodValues = sortFoodIds($recipeInfo['foodIds']);
    $recipeInfo['foodValues'] = $foodValues;

    // 追加処理
    $obj = new InsertSql('レシピの追加処理', 0);
    $recipeList = $obj->insertRecipeT($recipeInfo);

    // $_POSTを初期化
    $_POST = array();

    // 処理結果
    $result = $recipeList->getResult();
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


// selectでレシピ一覧をとってくる

// テスト用
$_POST['foodIds'] = [4, 5];
$_POST['flag'] = 0;

// POSTした値をコピーする
$foodIds = $_POST['foodIds'];
$flag = $_POST['flag'];

// foodIdをソート
$fValueStr = sortFoodIds($foodIds);

// SelectSqlでレシピ一覧を取得
$obj = new SelectSql('レシピ一覧を取得', 0);
$recipeList = $obj->getRecipe($fValueStr, $flag);

// 食材一覧を表示
// foreach($recipeList as $x) {
//     $foodIds[$x] = explodeFoodValues($recipeList[$x]['foodValues']);
// }

// for($i = 0; $i < count($recipeList); $i++) {
//     $foodIds[$i] = explodeFoodValues($recipeList[$i]['foodValues']);
// }

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "recipeManagement");
$vi->setAssign("h1Title", "レシピテーブル管理画面");
$vi->setAssign("main", "recipeManagement");
$vi->setAssign("userId", 1);
$vi->setAssign("recipeList", $recipeList);
$vi->setAssign("foodIds", $foodIds);

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
// echo '<pre>';

// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']['recipeList']);
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '$resultの配列';
// print_r($result);
// // echo '$foodIdsの配列';
// // print_r($foodIds);
// echo '$recipeInfoの配列';
// print_r($recipeInfo);
// echo '$_POSTの配列';
// print_r($_POST);

// echo '</pre>';