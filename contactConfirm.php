<?php

// セッションの開始
session_start();

// リファラチェック
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

// ファイルのインクルード
require_once 'view/View.php';


////////// 画面出力制御処理 //////////
// $_POSTを受ける変数の準備・初期化
$contactName = [];
$contactEmail = [];
$contactKinds = [];
$contactMessage = [];

// $_POSTの内容をそれぞれの変数に格納
$contactName = $_POST['name'];
$contactEmail = $_POST['email'];
$contactKinds = $_POST['kinds'];
$contactMessage = $_POST['message'];

// viewクラスの呼び出し
$vi = new View();

// $viに値を入れていく
$vi->setAssign('title', 'ippin | お問い合わせ内容確認画面');
$vi->setAssign('cssPath', 'css/user.css');
$vi->setAssign('bodyId', 'contactConfirm');
$vi->setAssign('main', 'contactConfirm');

// contact.phpから$_POSTで受け取った$contactName, $contactEmail, $contactKinds, $contactMessageを$viに渡す
$vi->setAssign('contactName',$contactName);
$vi->setAssign('contactEmail',$contactEmail);
$vi->setAssign('contactKinds',$contactKinds);
$vi->setAssign('contactMessage',$contactMessage);

// $viの値を$_SESSIONに渡して使えるようにする
$_SESSION['viewAry'] = $vi->getAssign();

// templateUserに$viを渡す
$vi ->screenView('templateUser');
