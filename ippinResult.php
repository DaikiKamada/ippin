<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// $_POSTを受ける変数の準備・初期化
$foodsSelect = [];
$foodsArray = [];
$foodsId = [];

// $_POSTの内容を$foodsSelectに格納
foreach ($_POST['foodsSelect'] as $p) {
    $foodsSelect[] = e($p);
}

// 配列の各要素を処理
foreach ($foodsSelect as $f) {
    if (is_string($f)) {
        list($id, $name) = explode(":", $f);
        $foodsArray[$id] = $name;
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

// foreach($recipeList as $x){
//     $foodIds = explodeFoodValues($recipeList[$x]['foodValues']);
// }
// print_r($recipeList);
$foodIds = [];
for($i = 0; $i < count($recipeList); $i++) {
    $foodIds[$i] = explodeFoodValues($recipeList[$i]['foodValues']);

}

$name = [];

// for($i = 0; $i <= count($foodIds); $i++) {
//     if(array_key_exists($i, $foodsArray)) {
//         $name[$i][] = $foodsArray[$i];
//     }
// }

// レシピ毎に必要な材料を表示する配列の配列を作成
for($i = 0; $i < count($recipeList); $i++) {
    for($x = 0; $x < count($foodsArray); $x++) {
        for($y = 0; $y <= count($foodsArray); $y++) {
            if($foodIds[$i][$x] == $y) {
                $name[$i][$x] = $foodsArray[$y];
            }
        }
    }
}

// foreach ($foodIds as $x) {
//     if(array_key_exists($x, $foodsArray)){
//     }
// }

// print 'これだよ<br>';
// print_r ($name);

// $recipeListの取得に失敗したらエラー処理、成功したら次の処理を実行
if (checkClass($recipeList)) {
    ///////////////////////////////// true : エラー処理する /////////////////////////////////
    echo '<p>ざんねん！その食材を使ったレシピはこの世に存在しないよ！</p>';
    echo '<a href="main.php">戻る</a>';
} else {
    // viewクラスの呼び出し
    $vi = new View();


$vi->setAssign("foodsName",$foodsName);

   // $viに値を入れていく
    $vi->setAssign("title",'ippin | 作れるippinの検索結果');
    $vi->setAssign('cssPath', 'css/user.css');
    $vi->setAssign("bodyId",'ippinResult');
    $vi->setAssign("main",'ippinResult');
    
    // main.phpから$_POSTで受け取った$foodsArrayを$viに渡す
    $vi->setAssign("foodsName",$foodsArray);


    // 取得したrecipeListを$viに渡す
    $vi->setAssign('recipeList', $recipeList);
    
    // $viの値を$_SESSIONに渡して使えるようにする
    $_SESSION['viewAry'] = $vi->getAssign();

    // templateUserに$viを渡す
    $vi->screenView('templateUser');
}

// デバッグ用※あとで消そうね！
echo '<pre>';
echo '$_SESSIONの配列';
print_r($_SESSION['viewAry']);
echo '$_POSTの配列';
print_r($_POST);
echo '$foodsArrayの配列';
print_r($foodsArray);
echo '$foodsIdの配列';
print_r($foodsId);
echo '$foodsNameの配列';
print_r($foodsName);
echo '$foodIdsの配列';
print_r($foodIds);
echo '$name';
print_r($name);
echo '</pre>';