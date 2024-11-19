<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// $_POSTを受ける変数の準備・初期化
$foodsSelect = [];
$foodsId = [];
$foodsName = [];

// $_POSTの内容を$foodsSelectに格納
$foodsSelect = $_POST['foodsSelect'];

// 配列の各要素を処理
foreach ($foodsSelect as $f) {
    if (is_string($f)) {
        list($id, $name) = explode(":", $f);
        $foodsId[] = $id;
        $foodsName[] = $name;
    }
}

// $_POSTで取得した$foodsIdを昇順にソートして$sortFoodsIdに格納する
$sortFoodsId = sortFoodIds($foodsId);

// SelectSqlのインスタンスを作成
$selectSql = new SelectSql('食材', 0);

// recipeListを取得
$recipeList = $selectSql->getRecipe($sortFoodsId, 0);

// $recipeListの取得に失敗したらエラー処理、成功したら次の処理を実行
if (checkClass($recipeList)) {
    ///////////////////////////////// true : エラー処理する /////////////////////////////////
} else {
    // viewクラスの呼び出し
    $vi = new View();

    // $viに値を入れていく 
    $vi->setAssign("title",'ippin | 作れるippinの検索結果');
    $vi->setAssign('cssPath', 'css/user.css');
    $vi->setAssign("bodyId",'ippinResult');
    $vi->setAssign("main",'ippinResult');
    
    // main.phpから$_POSTで受け取った$foodsNameを$viに渡す
    $vi->setAssign("foodsName",$foodsName);

    // 取得したrecipeListを$viに渡す
    $vi->setAssign('recipeList', $recipeList);
    
    // $viの値を$_SESSIONに渡して使えるようにする
    $_SESSION['viewAry'] = $vi->getAssign();

    // templateUserに$viを渡す
    $vi->screenView('templateUser');
}

// デバッグ用※あとで消そうね！
echo '<pre>';
print_r($_SESSION['viewAry']);
echo '</pre>';