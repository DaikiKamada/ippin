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
        // SESSIONにeditedRecipeキーが存在すればそれをコピー、なければPOSTの値をコピー ※ページをリロードしても大丈夫なように
        if (array_key_exists('editedRecipe', $_SESSION['viewAry'])) {
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            $recipeInfo = $_SESSION['viewAry']['recipeInfo'];
            $editedRecipe = $_SESSION['viewAry']['editedRecipe'];
            $FileInfo = $_FILES;
            
        } elseif (isset($_POST['choicedRecipe'])) {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeInfo = $_SESSION['viewAry']['recipeList'];
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
        

        ////////// recipe一覧の取得処理 //////////
        // 全ての食材を取得
        $selectFoods = new SelectSql('全ての食材を取得', 8);
        $allFoodsList = $selectFoods->getFood();


        ////////// 編集したいrecipe一覧の取得処理 //////////
        // 配列を用意(編集したいレシピを入れる)
        $editedRecipe = [];

        // 編集したいレシピの一覧を取得
        for ($i = 0; $i < count($recipeInfo); $i++) {
            for ($x = 0; $x < count($recipeIds); $x++) {
                if ($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
                    $editedRecipe[] = $recipeInfo[$i];
                }
            }
        }
        
        // recipeManagement.phpで選択した食材、レシピフラグをSESSIONに渡すために、変数にコピー
        $foodIds = $_SESSION['viewAry']['foodIds'];
        $flag = $_SESSION['viewAry']['flag'];


        ////////// 編集ボタンが押された時の処理 //////////
        if (array_key_exists('update', $_POST)) {
            if ($_POST['update'] == 'update') {
                $editedInfo = [];

                // POSTの内容をコピー
                $copyPost = $_POST;

                // if(!empty($_FILES)) {
                    
                //     ////////// 画像のアップロードの下処理 //////////
                //     $imgFileObj = new ImgFile('画像ファイル処理', 8);
                    
                //     // $_FILESの中身を1つずつ取り出す
                //     $fileInfos = [];
                //     for ($i = 0; $i < count($_FILES); $i++) {
                //         $fileInfos[$i]['upFile'] = $_FILES[$i.'upFile'];
                //     }
                    
                //     // $fileInfosの中身を1つずつ確認(アップロードできるか)
                //     $upFiles = [];
                //     for ($i = 0; $i < count($fileInfos); $i++) {
                //         $upFiles[] = $imgFileObj->checkUplodeFile(0, $fileInfos[$i], 1);
                //     }

                //     // checkUplodeFileの結果が失敗であれば、失敗結果を配列に追加
                //     $upFilesResult = [];
                //     for ($i = 0; $i < count($upFiles); $i++) {
                //         if(checkClass($upFiles[$i])){
                //             $upFilesResult[$i] = $upFiles[$i]->getResult();
                //         }
                //     }

                //     // $upFilesResult配列にオブジェクトが含まれていたら、エラー画面に遷移
                //     if (!empty($upFilesResult)){
                //         for ($i = 0; $i < count($upFilesResult); $i++) {
                //             if(!empty($upFilesResult[$i])) {
                //                 $vi->setAssign('resultNo', $upFilesResult[$i]['resultNo']);  // 処理結果No 0:エラー, 1:成功
                //                 $vi->setAssign('h1Title', $upFilesResult[$i]['resultTitle']); // エラーメッセージのタイトル
                //                 $vi->setAssign('resultMsg', $upFilesResult[$i]['resultMsg']); // エラーメッセージ
                //                 $vi->setAssign('linkUrl', $upFilesResult[$i]['linkUrl']);    // 戻るボタンに設置するリンク先
                //             }   
                //         }
                //         $vi->setAssign('title', 'ippin食材編集画面 | 画像処理結果'); // タイトルバー用
                //         $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                //         $vi->setAssign('bodyId', 'error');  // ？
                //         $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                        
                //         $_SESSION['viewAry'] = $vi->getAssign();
                //         $vi ->screenView('templateAdmin');
                //         exit;
                //     }
                // }

                // $editedInfoの中身を成形
                for ($i = 0; $i < count($editedRecipe); $i++) {
                    // $copyPostの、キーが数字の部分だけをコピー
                    $editedInfo[$i] = $copyPost[$i];
                    
                    // SESSIONから、userIdをコピー
                    $editedInfo[$i]['userId'] = $_SESSION['userId'];
                    
                    // 日付を取得して配列に追加
                    $lastUpdate = getDatestr();
                    $editedInfo[$i]['lastUpdate'] = $lastUpdate;
                    
                    // foodIdをソートして配列に追加
                    $foodValues = sortFoodIds($editedInfo[$i]['foodValues']);
                    $editedInfo[$i]['foodValues'] = $foodValues;

                    // recipeIdを配列に追加
                    $editedInfo[$i]['recipeId'] = $recipeIds[$i];
                    
                    // imgを作成
                    if ($fileInfos[$i]['upFile']['name'] != '') {
                        $removedImgPath[$i]['old'] = 'images/'.$editedRecipe[$i]['img'];
                        $removedImgPath[$i]['new'] = 'images/remove-'.$editedRecipe[$i]['img'];
                        rename($removedImgPath[$i]['old'], $removedImgPath[$i]['new']);

                    }

                    // 旧画像ファイルの名前を新画像ファイルにつける
                    $editedInfo[$i]['img'] = $editedRecipe[$i]['img'];

                }

                // アップデート処理を実行
                // UpdateSqlのインスタンスを作成
                $updateRecipe = new UpdateSql('レシピを更新', 9);
            
                // 複数のレコードを更新する
                $results = $updateRecipe->updateRecipeT($editedInfo);
// ここから挿入*********************************************************************************
                $resultArr = $results;
                $checkCount = 1;
                $resultNo = [];
                $recipeName = [];
                $resultMsg = [];                
                foreach ($resultArr as $key => $value) {
                    if (checkClass($value)) {
                        $resultObj = $value->getResult();
                        if ($resultObj['resultNo'] == 0) {
                            $checkCount = 0;

                        }

                        //テーブルデータを作成
                        $resultTxt[] = $resultObj['resultNo'] == 0? '失敗':'成功';
                        $recipeName[] = $recipeInfo[$key]['recipeName'];
                        $resultMsg[] = $resultObj['resultMsg'];
                        // $htmlText .= '<tr><td>' . ($resultObj['resultNo'] == 0? '失敗':'成功') . '</td>'.'<td>' . ($recipeInfo[$key]['recipeName']) . '</td>'.'<td>' . ($resultObj['resultMsg']) . '</td></tr>';

                    }
                }

                // 処理結果画面の値をViewインスタンスへセット
                $vi->setAssign('title', 'ippin食材編集画面 | レシピ更新処理結果'); // タイトルバー用
                $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                $vi->setAssign('bodyId', 'result');  // ？
                $vi->setAssign('main', 'result');    // テンプレート画面へインクルードするPHPファイル
                $vi->setAssign('h1Title', 'レシピ更新処理結果'); // エラーメッセージのタイトル
                $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                $vi->setAssign('resultTxt', $resultTxt); // 処理結果
                $vi->setAssign('recipeName', $recipeName); // レシピ名
                $vi->setAssign('resultMsg', $resultMsg); // エラーメッセージ
                $vi->setAssign('linkUrl', 'recipeManagement.php');    // 戻るボタンに設置するリンク先
                
                // $viの値を$_SESSIONに渡して使えるようにする
                $_SESSION['viewAry'] = $vi->getAssign();
                
                // templateAdminに$viを渡す
                $vi ->screenView('templateAdmin');
                exit;

// ここまで*********************************************************************************



                // // 結果を取得
                // $editResult = [];
                // foreach($results as $key) {
                //     $getResult = $key->getResult();
                //     $editResult[] = $getResult;

                // }

                // for ($i = 0; $i < count($editResult); $i++) {
                //     if ($editResult[$i]['resultNo'] == 0) {
                //         // エラー画面へ遷移
                //             $vi->setAssign('title', 'ippin食材編集画面 | 食材追加処理エラー'); // タイトルバー用
                //             $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                //             $vi->setAssign('bodyId', 'error');  // ？
                //             $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                //             $vi->setAssign('resultNo', $editResult[$i]['resultNo']);  // 処理結果No 0:エラー, 1:成功
                //             $vi->setAssign('h1Title', $editResult[$i]['resultTitle']); // エラーメッセージのタイトル
                //             $vi->setAssign('resultMsg', $editResult[$i]['resultMsg']); // エラーメッセージ
                //             $vi->setAssign('linkUrl', $editResult[$i]['linkUrl']);    // 戻るボタンに設置するリンク先
                        
                //         $_SESSION['viewAry'] = $vi->getAssign();
                //         $vi ->screenView('templateAdmin');
                //         exit;

                //     } else {
                //         // 新画像ファイルがアップロードされていれば、また、アップデートの結果をチェックし成功であれば、旧画像ファイルを削除し新画像ファイルをアップロードする
                //         if (!empty($removedImgPath[$i])) {

                //             if ($editResult[$i]['resultNo'] == 1) {
                //                 $fileUp = $imgFileObj->fileUplode($editedInfo[$i]['img'], $fileInfos[$i]);
                //                 if (checkClass($fileUp)) {
                //                     $resultArr = $fileUp->getResult();                                    
                //                     if ($resultArr['resultNo'] == 0) {
                //                         // エラー画面へ遷移
                //                             $vi->setAssign('title', 'ippin管理画面 | 食材編集処理エラー'); // タイトルバー用
                //                             $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                //                             $vi->setAssign('bodyId', 'error');  // ？
                //                             $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                //                             $vi->setAssign('resultNo', $resultArr['resultNo']);  // 処理結果No 0:エラー, 1:成功
                //                             $vi->setAssign('h1Title', $resultArr['resultTitle']); // エラーメッセージのタイトル
                //                             $vi->setAssign('resultMsg', $resultArr['resultMsg']); // エラーメッセージ
                //                             $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
                        
                //                         $_SESSION['viewAry'] = $vi->getAssign();
                //                         $vi ->screenView('templateAdmin');
                //                         exit;

                //                     }
                //                 }
                //                 unlink($removedImgPath[$i]['new']);
                //             }
                //         }
                //     }
                // }

                // // 処理を完了してrecipeManagementへリダイレクト
                // header('Location: recipeManagement.php');

            ////////// キャンセルボタンが押された時の処理 //////////
            } elseif ($_POST['update'] == 'cancel') {
                // 処理をせずにrecipeManagementへリダイレクト
                header('Location: recipeManagement.php');

            }
        }
            
        ////////// 画面出力制御処理 //////////
        // viewクラスの呼び出し

        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | レシピ編集画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'recipeEdit');
        $vi->setAssign('h1Title', 'レシピ編集画面');
        $vi->setAssign('main', 'recipeEdit');
        $vi->setAssign('editedRecipe', $editedRecipe);
        $vi->setAssign('allFoodsList', $allFoodsList);
        $vi->setAssign('recipeIds', $recipeIds);
        $vi->setAssign('recipeInfo', $recipeInfo);
        $vi->setAssign('foodIds', $foodIds);
        $vi->setAssign('flag', $flag);

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
