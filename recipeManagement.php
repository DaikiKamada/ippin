<!-- <pre> -->
<?php
session_start();

// ファイルのインクルード
require_once "common/SelectSql.php";
require_once "common/insertSql.php";
require_once 'common/Utilities.php';
require_once 'view/View.php';

////////// insert(追加ボタンを押した場合の処理) //////////
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


////////// selectでレシピ一覧を取得 //////////
$foodIds = [];

// SESSIONの['viewAry']に['foodIds']と['flag']があればコピーし、なければPOSTの中身をコピーする
if(array_key_exists('foodIds',$_SESSION['viewAry']) && array_key_exists('flag', $_SESSION['viewAry'])) {
    $foodIds = $_SESSION['viewAry']['foodIds'];
    $flag = $_SESSION['viewAry']['flag'];
}
else {
    $foodIds = $_POST['selectFoods'];
    $flag = $_POST['flag'];
}

// foodIdsをソート
$fValueStr = sortFoodIds($foodIds);

// SelectSqlでレシピ一覧を取得
$obj = new SelectSql('レシピ一覧を取得', 0);
$recipeList = $obj->getRecipe($fValueStr, $flag);

// recipeFlagを上書きする(0を非表示に、1を表示に、そのほかの場合は不明に)
for($i = 0; $i < count($recipeList); $i++) {
    if($recipeList[$i]['recipeFlag'] == 0) {
        $recipeList[$i]['recipeFlag'] = '非表示';
    }
    elseif($recipeList[$i]['recipeFlag'] == 1) {
        $recipeList[$i]['recipeFlag'] = '表示';
    }
    else {
        $recipeList[$i]['recipeFlag'] = '不明';
    }
}

// SelectSqlで食材名を取得
$foodsList = $obj->getSelectedFood($foodIds);

if(checkClass($recipeList)) {
    ///////////////////////////////// true : エラー処理する /////////////////////////////////
    echo '<p>たいへん！食材がうまく取得できないよ！管理人を呼んでね！</p>';
}
else {
    $vi = new View();
    
    $vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
    $vi->setAssign("cssPath", "css/admin.css");
    $vi->setAssign("bodyId", "recipeManagement");
    $vi->setAssign("h1Title", "レシピテーブル管理画面");
    $vi->setAssign("main", "recipeManagement");
    $vi->setAssign("userId", 1);
    $vi->setAssign("foodIds", $foodIds);
    $vi->setAssign("flag", $flag);
    $vi->setAssign("recipeList", $recipeList);
    $vi->setAssign("foodsList", $foodsList);
    
    $_SESSION['viewAry'] = $vi->getAssign();
    
    $vi ->screenView("templateAdmin");
}


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