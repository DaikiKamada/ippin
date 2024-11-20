<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/CountSql.php';
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// CountSqlSqlのインスタンスを作成
$CountSql = new CountSql('レシピ件数の取得', 0);

// レシピ件数を取得
$countRecipeAll = $CountSql->getCount('##', 9);
$countRecipeOn = $CountSql->getCount('##', 1);
$countRecipeOff = $CountSql->getCount('##', 0);

// レシピ件数の取得に失敗したらエラー処理、成功したら次の処理を実行
if (!isset($countRecipeAll) || !isset($countRecipeOn) || !isset($countRecipeOff)) {
    ///////////////////////////////// true : エラー処理する /////////////////////////////////
    echo '<p>たいへん！レシピ件数がうまく取得できないよ！管理人を呼んでね！</p>';
} else {
    // SelectSqlのインスタンスを作成
    $selectSql = new SelectSql('食材選択肢の取得', 0);
    
    // foodListを取得
    $foodsList = $selectSql->getFood();
    
    // $foodsListの取得に失敗したらエラー処理、成功したら次の処理を実行
    if (checkClass($foodsList)) {
        ///////////////////////////////// true : エラー処理する /////////////////////////////////
        echo '<p>たいへん！食材がうまく取得できないよ！管理人を呼んでね！</p>';
    } else {
        // viewクラスの呼び出し
        $vi = new View();
        
        // $viに値を入れていく
        $vi->setAssign("title", "ippin管理画面 | 管理者トップ画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "manageTop");
        $vi->setAssign("h1Title", "管理者トップ画面");
        $vi->setAssign("main", "manageTop");
        
        // $viに$countRecipeAll, $countRecipeOn, $countRecipeOffを入れる
        $vi->setAssign("countRecipeAll", $countRecipeAll);
        $vi->setAssign("countRecipeOn", $countRecipeOn);
        $vi->setAssign("countRecipeOff", $countRecipeOff);
        
        // 取得したfoodsListを渡す
        $vi->setAssign('foodsList', $foodsList);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();
        
        // templateUserに$viを渡す
        $vi ->screenView("templateAdmin");
    }
}

// デバッグ用※あとで消そうね！
// echo '<pre>';
// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']);
// echo '<br>';
// echo '$countRecipeAll:';
// print_r($countRecipeAll);
// echo '<br>';
// echo '$countRecipeOn:';
// print_r($countRecipeOn);
// echo '<br>';
// echo '$countRecipeOff:';
// print_r($countRecipeOff);
// echo '<br>';
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '<br>';
// echo '</pre>';