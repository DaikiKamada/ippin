<?php

// セッションの開始
session_start();

// リファラチェック
$refererUrl = '';
$refererResult = '';

$refererUrl = $_SERVER['HTTP_REFERER'];
$refererResult = (preg_match('|https?://[\w]+/|',$refererUrl));

if ($refererResult == 0) {
    $vi = new View();
        $vi->setAssign('title', 'ippin | アクセスエラー'); // タイトルバー用
        $vi->setAssign('cssPath', 'css/user.css');  // CSSファイルの指定
        $vi->setAssign('bodyId', 'error');  // ？
        $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
        $vi->setAssign('resultNo', 0);  // 処理結果No 0:エラー, 1:成功
        $vi->setAssign('h1Title', 'アクセスエラー'); // エラーメッセージのタイトル
        $vi->setAssign('resultMsg', '不正なアクセスです'); // エラーメッセージ
        $vi->setAssign('linkUrl', 'main.php');    // 戻るボタンに設置するリンク先

    $_SESSION['viewAry'] = $vi->getAssign();
    $vi ->screenView('templateAdmin');
    exit;

}

// ファイルのインクルード
require_once 'common/UrlCheck.php';
require_once 'common/UserLogin.php';
require_once 'common/SelectSql.php';
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
        $obj = new UrlCheckSql('リンク切れのレシピ一覧を表示',0, '##', 9);
        $lastView = $_SESSION['viewAry']['main'];
        if ($lastView == 'manageTop') {
            $noLinkRecipeList = $obj->checkRecipeUrls();
        } elseif ($lastView == 'result') {
                $rinkIdArr = [];
                foreach ($_SESSION['viewAry']['noLinkRecipeList'] as $value) {
                    $rinkIdArr[] = $value['recipeId'];
                }
                $obj = new SelectSql('リンク切れのレシピ一覧を表示', 0);
                $noLinkRecipeList = $obj->getSelectedRecipe($rinkIdArr);
        } else{
            if (array_key_exists('noLinkRecipeList', $_SESSION['viewAry']) && isset($_SESSION['viewAry']['noLinkRecipeList'])) {
                $noLinkRecipeList = $_SESSION['viewAry']['noLinkRecipeList'];
            } else {
                $noLinkRecipeList = $obj->checkRecipeUrls();
            }
        }
        // リンク切れ一覧の内容をチェック
        $noLinkMsg = '';
        if (checkClass($noLinkRecipeList)) {
            $noLinkMsg = 'リンク切れ一覧の取得に失敗しました';
        } else {
            if (count($noLinkRecipeList) == 0) {
                $noLinkMsg = 'リンク切れレシピは０件です';
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
        $vi->setAssign('noLinkMsg', $noLinkMsg);

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


