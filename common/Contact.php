<?php

session_start();
$contact = $_SESSION['viewAry'];

// ファイルのインクルード
require_once '../view/View.php';

function sendContactMail(string $name, string $email, string $case, string $naiyou, string $title) {
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");
    $message = messageCreate($name, $email, $case, $naiyou);
    mb_send_mail('nagoshi19th@gmail.com', $title, $message, 'From: nagoshi19th@gmail.com');
    print '送信成功';
    return true;
}

function messageCreate(string $name, string $email, string $case, string $naiyou):string {
    $message = "
    [送信者]
    {$name}
    [メールアドレス]
    {$email}
    [お問い合わせの種類]
    {$case}
    [内容]
    {$naiyou}";
    return $message;
}

$to = 'nagoshi19th@gmail.com';
$headers = "From: nagoshi19th@gmail.com . \r\n";

$title = $contact['contactKinds'].'についてのおといあわせ';
$name = $contact['contactName'];
$case = $contact['contactKinds'];
$naiyou = $contact['contactMessage'];
$email = $contact['contactEmail'];

if(sendContactMail($name, $email, $case, $naiyou, $title)) {
    // viewクラスの呼び出し
    $vi = new View();
    
    // $viに値を入れていく
    $vi->setAssign('title', 'ippin | トップページ');
    $vi->setAssign('cssPath', 'css/user.css');
    $vi->setAssign('bodyId', 'main');
    $vi->setAssign('main', 'main');
    $vi->setAssign('message', 'お問い合わせが完了しました！');
    
    // $viの値を$_SESSIONに渡して使えるようにする
    $_SESSION['viewAry'] = $vi->getAssign();
    
    // トップページにリダイレクト
    echo '<meta http-equiv="refresh" content="0;URL=../main.php">';

    // templateUserに$viを渡す
    $vi->screenView('templateUser');

} else {
    echo "メール送信失敗です";
    echo '<a href="../main.php">お出口はこちら</a>';

}
