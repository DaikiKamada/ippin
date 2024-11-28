<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/ImgFile.php';
require_once 'common/SelectSql.php';
require_once 'common/UpdateSql.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


$vi = new View();

$resultTxt = [];
$recipeName = [];
$resultMsg = [];
$resultNo = 1;

// デバッグ用※あとで消そうね！
echo '<pre>';

print_r($resultTxt);
echo '<br>';
print_r($recipeName);
echo '<br>';
print_r($resultMsg);
echo '<br>';

echo '</pre>';

////////// ユーザー認証処理 //////////
if (isset($_SESSION['userMail']) && isset($_SESSION['userPw'])) {
    // セッション情報から認証情報を取得し、権限があるかをチェック
    $userMail = $_SESSION['userMail'];
    $userPw = $_SESSION['userPw'];
    $userFlag = 0;
    $obj = new UserLogin('ユーザ認証処理', 6);    

    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    // ユーザ認証OK
    if ($result) {
        ////////// 食材一覧の取得処理 //////////
        

        // SESSIONにeditedRecipeキーが存在すればそれをコピー、なければPOSTの値をコピー ※ページをリロードしても大丈夫なように
        if (array_key_exists('editedRecipe', $_SESSION['viewAry'])) {
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            $recipeList = $_SESSION['viewAry']['recipeList'];
            $editedRecipe = $_SESSION['viewAry']['editedRecipe'];
            $FileInfo = $_FILES;
            
        } elseif (isset($_POST['choicedRecipe'])) {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeList = $_SESSION['viewAry']['recipeList'];
            $FileInfo = $_FILES;
            
        } else {
            // 失敗したらエラー画面へ遷移
                $vi->setAssign('title', 'ippin管理画面 | 編集レシピ取得エラー'); // タイトルバー用
                $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                $vi->setAssign('bodyId', 'error');  // ？
                $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                $vi->setAssign('resultNo', 0);  // 処理結果No 0:エラー, 1:成功
                $vi->setAssign('h1Title', '編集レシピ取得エラー'); // エラーメッセージのタイトル
                $vi->setAssign('resultMsg', '編集レシピの取得に失敗しました'); // エラーメッセージ
                $vi->setAssign('linkUrl', 'recipeManagement');    // 戻るボタンに設置するリンク先
            
            $_SESSION['viewAry'] = $vi->getAssign();
            $vi ->screenView('templateAdmin');
            exit;

        }

        ////////// 編集したいrecipe一覧の取得処理 //////////
        
        $selectFoods = new SelectSql('全ての食材を取得', 8);

        // 全ての食材一覧が未取得であれば取得し、取得済みであれば$_SESSIONからコピー
        if(!array_key_exists('allFoodsList', $_SESSION['viewAry'])){
            $allFoodsList = $selectFoods->getFood();
        }
        else {
            $allFoodsList = $_SESSION['viewAry']['allFoodsList'];
        }
        
        // 配列を用意(編集したいレシピを入れる)
        $editedRecipe = [];

        // 編集したいレシピの一覧を取得
        for ($i = 0; $i < count($recipeList); $i++) {
            for ($x = 0; $x < count($recipeIds); $x++) {
                if ($recipeList[$i]['recipeId'] == $recipeIds[$x]) {
                    $editedRecipe[] = $recipeList[$i];
                }
            }
        }

        // ボタンが押された場合の処理
        if (array_key_exists('update', $_POST)) {
            ////////// 編集ボタンが押された時の処理 //////////
            if ($_POST['update'] == 'update') {
                
                // 画像をチェック
                if(isset($_FILES)) {
                    
                    // $_FILESの中身をコピー
                    $checkFiles = $_FILES;

                    // $checkFilesの中身を1つずつ取り出す
                    $checkFilesArr = [];
                    for ($i = 0; $i < count($checkFiles); $i++) {
                        $checkFilesArr[$i]['upFile'] = $checkFiles[$i.'upFile'];
                    }
                    
                    // ImgFileのクラスを作成
                    $fileCheckObj = new ImgFile('画像ファイル処理', 8);
                    
                    // $_FILESの中身を一つずつ確認し、nullでなければ(画像がアップロードされていれば)画像をチェックする
                    $imgChecked = [];
                    $imgInfo = [];
                    for($i = 0; $i < count($checkFilesArr); $i++) {
                        if(isset($checkFilesArr[$i])) {
                            $imgCheck = $fileCheckObj->checkUplodeFile(0, $checkFilesArr[$i], 1);
                            if(checkClass($imgCheck)) {
                                $imgChecked = $imgCheck->getResult();
                                // エラー処理
                                $vi->setAssign('resultNo', 0); 
                                $resultTxt[$i] = $imgChecked['resultTitle'];
                                $recipeName[$i] = $editedRecipe[$i]['recipeName'];
                                $resultMsg[$i] = $imgChecked['resultMsg'];
                                $resultNo = 0;

                            } else {
                                $imgInfo[$i]['upFile'] = $checkFiles[$i.'upFile'];
                            }
                            $imgChecked[] = $imgCheck;
                        }
                    }
                    
                    // 画像がアップロード出来ない形式であれば、アップデートするレシピ一覧から削除し、アップロード出来る形式であればimgを配列に追加
                    $removedImgPath = [];
                    $img = [];
                    for ($i = 0; $i < count($imgChecked); $i++) {
                        if(isset($imgChecked[$i])) {
                            if(checkClass($imgChecked[$i])) {
                                // 配列から削除
                                unset($editedRecipe[$i]);

                            } else {
                                // 旧画像ファイルを処理
                                $removedImgPath[$i]['old'] = 'images/'.$editedRecipe[$i]['img'];
                                $removedImgPath[$i]['new'] = 'images/remove-'.$editedRecipe[$i]['img'];
                                rename($removedImgPath[$i]['old'], $removedImgPath[$i]['new']);
                                
                                $img[$i] = $editedRecipe[$i]['img'];
                            }
                        }
                    }
                }

                // $_POSTの内容をコピー
                $editInfo = $_POST;

                // 編集内容を成形
                $editedRecipeInfo = [];

                for ($i = 0; $i < count($editedRecipe); $i++) {
                    if(isset($editedRecipe[$i])) {
                        // 画像ファイルがある場合、imgに画像を追加
                        if (isset($img[$i])) {
                            $editedRecipeInfo[$i]['img'] = $img[$i];
                        }
                        else {
                            $editedRecipeInfo[$i]['img'] = $editedRecipe[$i]['img'];
                        }

                        // recipeNameを追加
                        $editedRecipeInfo[$i]['recipeName'] = $editInfo[$i]['recipeName'];

                        // foodIdをソート
                        $foodValues = sortFoodIds($editInfo[$i]['foodValues']);
                        $editedRecipeInfo[$i]['foodValues'] = $foodValues;
                        
                        // URLを追加
                        $editedRecipeInfo[$i]['url'] = $editInfo[$i]['url'];
                        
                        // howtoIdを追加
                        $editedRecipeInfo[$i]['howtoId'] = $editInfo[$i]['howtoId'];
                        
                        // commentを追加
                        $editedRecipeInfo[$i]['comment'] = $editInfo[$i]['comment'];
                        
                        // recipeFlagを追加
                        $editedRecipeInfo[$i]['recipeFlag'] = $editInfo[$i]['recipeFlag'];
                        
                        // memoを追加
                        $editedRecipeInfo[$i]['memo'] = $editInfo[$i]['memo'];
                        
                        // userIdを追加
                        $editedRecipeInfo[$i]['userId'] = $_SESSION['userId'];

                        // 日時を取得
                        $date = getDatestr();   
                        $editedRecipeInfo[$i]['lastUpdate'] = $date;

                        // siteNameを追加
                        $editedRecipeInfo[$i]['siteName'] = $editInfo[$i]['siteName'];

                        // recipeIdを配列に追加
                        $editedRecipeInfo[$i]['recipeId'] = $recipeIds[$i];

                    }
                }

                // 画像に問題がなければアップデート
                $updateObj = new UpdateSql('レシピ更新処理', 8);
                $updated = $updateObj->updateRecipeT($editedRecipeInfo);

                // アップデート結果を取得
                $updateResults = [];
                foreach($updated as $key) {
                    $result = $key->getResult();
                    $updateResults[] = $result;
                }

                // アップデート結果をviewに渡す
                for($i = 0; $i < count($updateResults); $i++) {
                    $resultTxt[$i] = $updateResults[$i]['resultTitle'];
                    $recipeName[$i] = $editedRecipe[$i]['recipeName'];
                    $resultMsg[$i] = $updateResults[$i]['resultMsg'];
                }

                // アップデートが成功したら画像をアップロード
                $fileUpResults = [];
                for($i = 0; $i < count($img); $i++) {
                    if(isset($imgInfo[$i])) {
                        $fileUp = $fileCheckObj->fileUplode($img[$i], $imgInfo[$i]);
                        if(checkClass($fileUp)) {
                            $fileUpResults[$i] = $fileUp->getResult();
                            // エラー処理
                            $vi->setAssign('resultNo', 0); 
                            $resultTxt[$i] = $fileUpResults[$i]['resultTitle'];
                            $recipeName[$i] = $editedRecipe[$i]['recipeName'];
                            $resultMsg[$i] = $fileUpResults[$i]['resultMsg'];
                            $resultNo = 0;
                        }
                        unlink($removedImgPath[$i]['new']);
                    }
                }

                // すべての処理が終わったら、result.phpに遷移
                $vi->setAssign('title', 'ippin管理画面 | レシピ編集結果');
                $vi->setAssign('cssPath', 'css/admin.css');
                $vi->setAssign('bodyId', 'result');
                $vi->setAssign('h1Title', 'レシピ編集結果');
                $vi->setAssign('main', 'result');
                $vi->setAssign('resultNo', $resultNo); 

                // 処理結果関係
                $vi->setAssign('recipeName', $recipeName);
                $vi->setAssign('resultTxt', $resultTxt);
                $vi->setAssign('resultMsg', $resultMsg);

                $_SESSION['viewAry'] = $vi->getAssign();
                $vi ->screenView('templateAdmin');
                exit;

            } elseif ($_POST['update'] == 'cancel') {
                ////////// キャンセルボタンが押された時の処理 //////////
                header('recipeManagement.php');

            }
        }
            
        ////////// 画面出力制御処理 //////////

        // $viに値を入れていく
        // 処理結果画面に遷移するよう作り変える //
        $vi->setAssign('title', 'ippin管理画面 | レシピ編集画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'recipeEdit');
        $vi->setAssign('h1Title', 'レシピ編集画面');
        $vi->setAssign('main', 'recipeEdit');
        $vi->setAssign('editedRecipe', $editedRecipe);
        $vi->setAssign('allFoodsList', $allFoodsList);
        $vi->setAssign('recipeIds', $recipeIds);
        $vi->setAssign('recipeList', $recipeList);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi ->screenView('templateAdmin');

    } else {
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView('templateUser');
    
    }
} else {
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateUser');

}


// デバッグ用※あとで消そうね！
// echo '<pre>';

// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';
// echo '$editedRecipeの配列';
// print_r($editedRecipe);
// echo '$recipeInfoの配列';
// print_r($recipeInfo);
// echo '$editedInfoの配列';
// print_r($editedeInfo);
// echo '<br>';
// print_r($results);
// echo '<br>';
// print_r($editResult);
// echo '<br>';
// echo '</pre>';