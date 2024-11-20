<?php
session_start();

require_once "common/UrlCheck.php";
require_once 'common/Utilities.php';
require_once 'view/View.php';

// SelectSqlでリンク切れしたレシピ一覧を取得
$obj = new UrlCheckSql('リンク切れのレシピ一覧を表示',0, '##', 9);
$noLinkRecipeList = $obj->checkRecipeUrls();

$vi = new View();

$vi->setAssign("title", "ippin管理画面 | リンク切れチェック画面");
$vi->setAssign("cssPath", "css/admin.css");
$vi->setAssign("bodyId", "linkCheck");
$vi->setAssign("h1Title", "リンク切れチェック画面");
$vi->setAssign("main", "linkCheck");
$vi->setAssign("noLinkRecipeList", $noLinkRecipeList);

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");
