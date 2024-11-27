<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/UrlCheck.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


////////// ユーザー認証処理 //////////
// セッション情報から認証情報を取得し、権限があるかをチェック
if (isset($_SESSION['userMail']) && isset($_SESSION['userPw'])) {
    $userMail = $_SESSION['userMail'];
    $userPw = $_SESSION['userPw'];
    $userFlag = 0;
    $obj = new UserLogin('ユーザ認証処理', 6);
    
    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    if ($result) { 
        ////////// 画面出力制御処理 //////////
        // SelectSqlでリンク切れしたレシピ一覧を取得
        if (isset($_SESSION['viewAry']['main']) && $_SESSION['viewAry']['main'] == 'manageTop') {
            $obj = new UrlCheckSql('リンク切れのレシピ一覧を表示',0, '##', 9);
            $noLinkRecipeList = $obj->checkRecipeUrls();
        } else{
            if (array_key_exists('noLinkRecipeList', $_SESSION['viewAry']) && isset($_SESSION['viewAry']['noLinkRecipeList'])) {
                // if (isset($_SESSION['noLinkRecipeList'])) {           
            // $rinkarr = $_SESSION['viewAry']['noLinkRecipeList'];
                // $rinkIdArr = [];
                // foreach ($_SESSION['viewAry']['noLinkRecipeList'] as $value) {
                //     $rinkIdArr[] = $value['recipeId'];
                // }
                // $obj = new SelectSql('リンク切れのレシピ一覧を表示', 0);
                // $noLinkRecipeList = $obj->getSelectedFood($rinkIdArr);
                $noLinkRecipeList = $_SESSION['viewAry']['noLinkRecipeList'];
            } else {
                $obj = new UrlCheckSql('リンク切れのレシピ一覧を表示',0, '##', 9);
                $noLinkRecipeList = $obj->checkRecipeUrls();
            }
        }

        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | リンク切れチェック画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'linkCheck');
        $vi->setAssign('h1Title', 'リンク切れチェック画面');
        $vi->setAssign('main', 'linkCheck');
        $vi->setAssign('noLinkRecipeList', $noLinkRecipeList);

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


