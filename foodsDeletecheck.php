<?php

// セッションの開始
session_start();

// ファイルのインクルード
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
        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $delId = $_GET['id'];
        $viewAry = $_SESSION['viewAry'];
        $foodAry = $viewAry['foodsList'];

        foreach ($foodAry as $obj) {
            if ($obj['foodId'] == $delId) { 
                $deleteInfo = $obj;
                break;
            }
        }
        
        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | 食材マスタ削除確認');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'foodsDeleteCheck');
        $vi->setAssign('h1Title', '食材マスタ削除確認');
        $vi->setAssign('main', 'foodsDeleteCheck');
        $vi->setAssign('delId', $delId);
        $vi->setAssign('foodCatM', $viewAry['foodCatM']);
        $vi->setAssign('deleteInfo', $deleteInfo);
        
        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();
        
        // templateAdminに$viを渡す
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
echo '<pre>';

// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
echo '$_GETの配列';
print_r($_GET);
echo '<br>';

echo '</pre>';
