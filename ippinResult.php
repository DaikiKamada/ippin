<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// リファラチェック（AWS環境でのみONにしよう！）
// $refererUrl = '://1ppin.com/';
// preg_match('|://[\S]+/|',$_SERVER['HTTP_REFERER'],$refererResult);

// if ($refererUrl != $refererResult[0]) {
//     $vi = new View();
//         $vi->setAssign('title', 'ippin | アクセスエラー'); // タイトルバー用
//         $vi->setAssign('cssPath', 'css/user.css');  // CSSファイルの指定
//         $vi->setAssign('bodyId', 'error');  // ？
//         $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
//         $vi->setAssign('resultNo', 0);  // 処理結果No 0:エラー, 1:成功
//         $vi->setAssign('h1Title', 'アクセスエラー'); // エラーメッセージのタイトル
//         $vi->setAssign('resultMsg', '不正なアクセスです'); // エラーメッセージ
//         $vi->setAssign('linkUrl', 'main.php');    // 戻るボタンに設置するリンク先

//     $_SESSION['viewAry'] = $vi->getAssign();
//     $vi ->screenView('templateAdmin');
//     exit;

// }


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
        list($id, $name) = explode(':', $f);
        $foodsArray[$id] = $name;
        $foodsId[] = $id;
        $foodsName[] = $name;

    }
}

// $_POSTで取得した$foodsIdを昇順にソートして$sortFoodsIdに格納する
$sortFoodsId = sortFoodIds($foodsId);

// SelectSqlのインスタンスを作成
$selectSql = new SelectSql('レシピの取得', 2);

// recipeListを取得
$recipeList = $selectSql->getRecipe($sortFoodsId, 1);

// $recipeListの取得に失敗したらエラー処理、成功したら次の処理を実行
if (checkClass($recipeList)) {
    $resultArr = $recipeList->getResult();

    if ($resultArr['resultNo'] == 0) {
        // エラー画面へ遷移
        $vi = new View();
            $vi->setAssign('title', 'ippin | recipe検索結果'); // タイトルバー用
            $vi->setAssign('cssPath', 'css/user.css');  // CSSファイルの指定
            $vi->setAssign('bodyId', 'error');  // ？
            $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
            $vi->setAssign('resultNo', $resultArr['resultNo']);  // 処理結果No 0:エラー, 1:成功
            $vi->setAssign('h1Title', $resultArr['resultTitle']); // エラーメッセージのタイトル
            $vi->setAssign('resultMsg', $resultArr['resultMsg']); // エラーメッセージ
            $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先

        $_SESSION['viewAry'] = $vi->getAssign();
        $vi ->screenView('templateUser');
        exit;

    } else {
        echo '<p>ざんねん！その食材を使ったレシピはこの世に存在しないよ！</p>';
        echo '<a href="main.php">戻る</a>';

    }
} else {
    // 配列$foodIdsに、レシピ毎に必要な材料のIDを格納
    $foodIds = [];
    for($i = 0; $i < count($recipeList); $i++) {
        $foodIds[$i] = explodeFoodValues($recipeList[$i]['foodValues']);

    }
    
    // レシピ毎に必要な材料を表示する配列の配列を作成
    $foodNameArray = [];
    for($i = 0; $i < count($recipeList); $i++) {
        for($x = 0; $x < count($foodsArray); $x++) {
            for($y = 0; $y <= max($foodIds[$i]); $y++) {
                if($foodIds[$i][$x] == $y) {
                    $foodNameArray[$i][$x] = $foodsArray[$y];

                }
            }
        }
    }
    
    // viewクラスの呼び出し
    $vi = new View();
    
    // $viに値を入れていく
    $vi->setAssign('title','ippin | 作れるippinの検索結果');
    $vi->setAssign('cssPath', 'css/user.css');
    $vi->setAssign('bodyId','ippinResult');
    $vi->setAssign('main','ippinResult');

    // main.phpから$_POSTで受け取った$foodsArrayを$viに渡す
    $vi->setAssign('foodsName',$foodsArray);
    
    // 取得した$foodNameArrayを$viに渡す
    $vi->setAssign('foodNameArray',$foodNameArray);
    
    // 取得した$recipeListを$viに渡す
    $vi->setAssign('recipeList', $recipeList);
    
    // $viの値を$_SESSIONに渡して使えるようにする
    $_SESSION['viewAry'] = $vi->getAssign();
    
    // templateUserに$viを渡す
    $vi->screenView('templateUser');

}


// デバッグ用※あとで消そうね！
// echo '<pre>';
// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$foodsArrayの配列';
// print_r($foodsArray);
// echo '<br>';
// echo '$foodsIdの配列';
// print_r($foodsId);
// echo '<br>';
// echo '$foodsNameの配列';
// print_r($foodsName);
// echo '<br>';
// echo '$foodIdsの配列';
// print_r($foodIds);
// echo '<br>';
// echo '$foodNameArrayの配列';
// print_r($foodNameArray);
// echo '<br>';
// echo '</pre>';
