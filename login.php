<?php
session_start();
require_once 'view/View.php';
require_once 'UserLogin.php';
require_once 'Utilities.php';

$userMail = e($_POST['mailAddress']);
$userPw = trim(e($_POST['password']));
$userFlag = 0;
$obj = new UserLogin('ユーザ認証処理', 0);

if (isset($userMail) && strlen($userPw)) {
    // ユーザ認証を実行
    $result = $obj->getUserInfo($userMail, sha1($userPw), $userFlag);
    // 認証結果をチェック
    if (checkClass($result)) { 
        // 処理結果を配列にセット
        $resultArr = $result->getResult();  // 配列を取得
        // エラー画面へ遷移
        $vi = new View();
            $vi->setAssign("title", "ippin管理画面 | エラー"); // タイトルバー用
            $vi->setAssign("cssPath", "css/admin.css");  // CSSファイルの指定
            $vi->setAssign("bodyId", "error");  // ？
            $vi->setAssign("main", "error");    // テンプレート画面へインクルードするPHPファイル
            $vi->setAssign("resultNo", $resultArr['resultNo']);  // 処理結果No 0:エラー, 1:成功
            $vi->setAssign("h1Title", $resultArr['resultTitle']); // エラーメッセージのタイトル
            $vi->setAssign("resultMsg", $resultArr['resultMsg']); // エラーメッセージ
            $vi->setAssign("linkUrl", $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
            
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi ->screenView("templateAdmin");

    } else {
        // セッションにログイン情報をセット
        $_SESSION['userMail'] = $userMail;
        $_SESSION['userPw'] = $userPw;
        $_SESSION['userName'] = $result['nickName'];
        $_SESSION['userFlag'] = $userFlag;
    }
} else {
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView("templateAdmin");
}

$test = 0;
// // ****************************************************************************
// // セッション情報から認証情報を取得し、権限があるかをチェック
// $userMail = $_SESSION['userMail'];
// $userPw = $_SESSION['userPw'];
// $userFlag = 0;
// $obj = new UserLogin('ユーザ認証処理', 0);
// if (isset($userMail) && isset($userPw)) {
//     // ユーザ認証を実行
//     $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
//     if ($result) { 
//         // 認証情報が正しい場合の処理を記載

//     } else {
//         $vi = $obj->getLoginErrView();
//         $_SESSION['viewAry'] = $vi->getAssign();
//         $vi->screenView("templateAdmin");
//     }
// } else {
//     $vi = $obj->getLoginErrView();
//     $_SESSION['viewAry'] = $vi->getAssign();
//     $vi->screenView("templateAdmin");
// }

// // ****************************************************************************

// require_once "view/View.php";


// $vi = new View();

// $vi->setAssign("title", "ippin | ログイン");
// $vi->setAssign("cssPath", "css/user.css");
// $vi->setAssign("bodyId", "login");
// $vi->setAssign("main", "login");

// $_SESSION['viewAry'] = $vi->getAssign();

// $vi ->screenView("templateUser");
