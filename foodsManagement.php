<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/InsertSql.php';
require_once 'common/SelectSql.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


////////// ユーザー認証処理 //////////
// セッション情報から認証情報を取得し、権限があるかをチェック
$userMail = $_SESSION['userMail'];
$userPw = $_SESSION['userPw'];
$userFlag = 0;
$obj = new UserLogin('ユーザ認証処理', 0);

if (isset($userMail) && isset($userPw)) {
    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    if ($result) { 
        ////////// 画面出力制御処理 //////////
       // insert(追加ボタンを押した場合の処理)
    
       if (isset($_POST['insert'])) {    
            // $_POSTの内容を$foodInfoに格納
            $foodInfo = [];
            $foodInfo = [
                'foodName' => e($_POST['foodName']),
                'foodCatId' => e($_POST['foodCatId'])
            ];
            
            // 追加処理
            $obj = new InsertSql('食材の追加処理', 0);
            $foodsList = $obj->insertFoodM($foodInfo);

            // $_POSTを初期化
            $_POST = [];

            // 処理結果
            $result = $foodsList->getResult();

            if($result['resultNo'] == 0) {
                // 追加できなかったよというJSのアラート
                echo '<script>alert("追加に失敗しました。再度お試しください。");</script>';
    
            } else {
                // 追加しましたよというJSのアラート
                echo '<script>alert("追加に成功しました！");</script>';
    
            } 
    
        } else {
            $foodInfo = [];
    
        }

        // 食材カテゴリを取得
        $obj = new SelectSql('食材カテゴリを取得', 0);
        $foodCatM = $obj->getFoodCatM();

        // 食材一覧を取得
        $obj = new SelectSql('食材一覧を取得', 0);
        $foodsList = $obj->getFoodList();

        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | 食材マスタ管理画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'foodsManagement');
        $vi->setAssign('h1Title', '食材マスタ管理画面');
        $vi->setAssign('main', 'foodsManagement');

        // $viにuserId, $foodCatM, $foodsListを入れる
        $vi->setAssign('userId', 1);
        $vi->setAssign('foodCatM', $foodCatM);
        $vi->setAssign('foodsList', $foodsList);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi ->screenView('templateAdmin');
    
    } else {
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView('templateAdmin');
    
    }

} else {
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateAdmin');

}


// デバッグ用※あとで消そうね！
// echo '<pre>';
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$foodInfoの配列';
// print_r($foodInfo);
// echo '<br>';
// echo '$foodInfoArrayの配列';
// print_r($foodInfoArray);
// echo '<br>';
// echo '$foodNameの配列';
// print_r($foodName);
// echo '<br>';
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '<br>';
// echo '$foodCatIdの配列';
// print_r($foodCatId);
// echo '<br>';
// echo '</pre>';
