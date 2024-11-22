<?php
session_start();

// ファイルのインクルード
require_once "common/SelectSql.php";
require_once "common/insertSql.php";
require_once 'common/Utilities.php';
require_once 'common/ImgFile.php';
require_once 'view/View.php';

////////// insert(追加ボタンを押した場合の処理) //////////
if (array_key_exists('insert',$_POST)) {

    $fileCheckObj = new ImgFile('画像ファイル処理', 0);
    $NewRecipeId = $fileCheckObj->getNewRecipeId();
    $fileCheck = $fileCheckObj->checkUplodeFile($NewRecipeId);

    // POSTの内容を$resipeInfoにコピー
    $recipeInfo = $_POST;
    $recipeInfo['foodIds'] = $_SESSION['viewAry']['foodIds'];
    $recipeInfo['userId'] = $_SESSION['userId'];
    $recipeInfo['img'] = $fileCheck;

    if (checkClass($fileCheck)) { 
        //エラー画面に遷移？
        $resultArr = $fileCheck->getResult(); 
    } else {
        $img = $fileCheck;
        // インサート OR アップデート処理を実行
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
            // SQLが正常実行の場合、画像ファイルをアップロード
            // エラーの場合、ファイルの修正を促すメッセージを表示
            $fileUp = $fileCheckObj->fileUplode($img);
            if (checkClass($fileUp)) {
                //エラー画面に遷移？
                $resultArr = $fileUp->getResult(); 
            }
        }
    }
}
else {
    $recipeInfo = [];
}

// ////////// selectでレシピ一覧を取得 //////////
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

if(checkClass($recipeList)) {
    // レシピがなかった場合の処理
    print_r($recipeList);
    echo '<a href="manageTop.php">戻る</a>';
}
else {
    // レシピがあった場合の処理

    // // recipeFlagを上書きする(0を非表示に、1を表示に、そのほかの場合は不明に)
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
        $vi->setAssign("foodIds", $foodIds);
        $vi->setAssign("flag", $flag);
        $vi->setAssign("recipeList", $recipeList);
        $vi->setAssign("foodsList", $foodsList);
        
        $_SESSION['viewAry'] = $vi->getAssign();
        
        $vi ->screenView("templateAdmin");
    }
}


// デバッグ用※あとで消そうね！
// echo '<pre>';

// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';

// echo '</pre>';
