<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once "view/View.php";
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';


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
        $vi->setAssign("title", "ippin管理画面 | 食材マスタ編集画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "foodsEdit");
        $vi->setAssign("h1Title", "食材マスタ編集画面");
        $vi->setAssign("main", "foodsEdit");
        
        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();
        
        // templateUserに$viを渡す
        $vi ->screenView("templateAdmin");
    
    } else {
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView("templateAdmin");
    
    }

} else {
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView("templateAdmin");

}