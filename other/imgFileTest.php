<?php

require_once 'common/ImgFile.php';

$obj = new ImgFile('画像ファイル処理', 0);
$NewRecipeId = $obj->getNewRecipeId();
$fileCheck = $obj->checkUplodeFile($NewRecipeId);
if (checkClass($fileCheck)) { 
    //エラー画面に遷移？
    $resultArr = $fileCheck->getResult(); 
} else {
    $img = $fileCheck;
    // インサート OR アップデート処理を実行
    // SQLが正常実行の場合、画像ファイルをアップロード
    // エラーの場合、ファイルの修正を促すメッセージを表示
    $fileUp = $obj->fileUplode($img);
    if (checkClass($fileUp)) {
        //エラー画面に遷移？
        $resultArr = $fileUp->getResult(); 
    }
    
}