<?php

// セッションを開始
session_start();

// セッションの全データを削除
$_SESSION = [];

// セッションを破棄
session_destroy();

// main.php にリダイレクト
header("Location: main.php");
exit;


// デバッグ用※あとで消そうね！
// echo '<pre>';

// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';

// echo '</pre>';
