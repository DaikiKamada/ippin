<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/DbManager.php';
require_once 'common/SelectSql.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


// 管理者ユーザー生成用
// $test = new UserLogin('テスト', 0);
// $test2 = $test->changePassword('nagoshi@gmail.com', sha1('1a!'));
// テスト forさとうさん

// DB接続をチェック
$dbh = new DbManager();

// DB接続エラーが発生している場合、エラー画面（サービス停止）に遷移
if (checkClass($dbh->getDb())) {
    $vi = $dbh->getDbhErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateUser');
    
}

// SelectSqlのインスタンスを作成
$selectSql = new SelectSql('食材リストの取得', 0);

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
    $vi->setAssign('title', 'ippin | トップページ');
    $vi->setAssign('cssPath', 'css/user.css');
    $vi->setAssign('bodyId', 'main');
    $vi->setAssign('main', 'main');
    
    // 取得したfoodsListを渡す
    $vi->setAssign('foodsList', $foodsList);
    
    // $viの値を$_SESSIONに渡して使えるようにする
    $_SESSION['viewAry'] = $vi->getAssign();
    
    // templateUserに$viを渡す
    $vi->screenView('templateUser');

}

// デバッグ用※あとで消そうね！
echo '<pre>';

echo '$_SESSIONの配列';
print_r($_SESSION['viewAry']);
echo '<br>';

echo '</pre>';
