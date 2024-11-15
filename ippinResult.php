<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// $_POSTを受ける変数の準備・初期化
$foodsSelect = [];
$foodsId = [];
$foodsName = [];

// $_POSTの内容を$foodsSelectに格納
$foodsSelect = $_POST['foodsSelect'];

// 配列の各要素を処理
foreach ($foodsSelect as $f) {
    if (is_string($f)) {
        list($id, $name) = explode(":", $f);
        $foodsId[] = $id;
        $foodsName[] = $name;
    }
}

// エラーコードの初期値を設定
$errorCode = 0;

// viewクラスの呼び出し
$vi = new View();

$vi->setAssign('cssPath', 'css/user.css');
$vi->setAssign("foodsName",$foodsName);

// $_SESSION['viewAry']のページ情報の更新
$_SESSION['viewAry']['title'] = 'ippin | 作れるippinの検索結果';
$_SESSION['viewAry']['bodyId'] = 'ippinResult';
$_SESSION['viewAry']['main'] = 'ippinResult';

// viewを表示
$vi->screenView('templateUser');
