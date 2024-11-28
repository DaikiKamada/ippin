<?php

// リファラチェック
// xampp環境用
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


// AWS環境用(ドメインvar)
$refererUrl = '://1ppin.com/';
preg_match('|://[\S]+/|',$_SERVER['HTTP_REFERER'],$refererResult);

if ($refererUrl != $refererResult[0]) {
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
