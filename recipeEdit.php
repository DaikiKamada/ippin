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


// デバッグ用※あとで消そうね！
echo '<pre>';

echo '$_SESSIONの配列';
print_r($_SESSION['viewAry']['editedRecipe']);

echo '</pre>';

////////// ユーザー認証処理 //////////
// セッション情報から認証情報を取得し、権限があるかをチェック
$userFlag = 0;
$obj = new UserLogin('ユーザ認証処理', 6);

if (isset($_SESSION['userMail']) && isset($_SESSION['userPw'])) {
    $userMail = $_SESSION['userMail'];
    $userPw = $_SESSION['userPw'];

    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    if ($result) {
        
        // SESSIONにeditedRecipeキーが存在すればそれをコピー、なければPOSTの値をコピー ※ページをリロードしても大丈夫なように
        if (array_key_exists('editedRecipe', $_SESSION['viewAry'])) {
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            $recipeInfo = $_SESSION['viewAry']['recipeInfo'];
            $editedRecipe = $_SESSION['viewAry']['editedRecipe'];
            $FileInfo = $_FILES;
            
        } else {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeInfo = $_SESSION['viewAry']['recipeList'];
            $FileInfo = $_FILES;
            
        }
        

        ////////// recipe一覧の取得処理 //////////
        // 全ての食材を取得
        $selectFoods = new SelectSql('全ての食材を取得', 0);
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

        if (array_key_exists('update', $_POST)) {
            if ($_POST['update'] == 'update') {
            $editedInfo = [];

            // POSTの内容をコピー
            $copyPost = $_POST;

            ////////// 画像のアップロードの下処理 //////////
            $obj = new ImgFile('画像ファイル処理', 0);

            // $_FILESの中身を1つずつ取り出す
            $fileInfos = [];
            for ($i = 0; $i < count($_FILES); $i++) {
                $fileInfos[$i]['upFile'] = $_FILES[$i.'upFile'];

            }

            // $editedInfoの中身を成形
            for($i = 0; $i < count($editedRecipe); $i++) {
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
                if($fileInfos[$i]['upFile']['name'] != '') {
                    $removedImgPath[$i]['old'] = 'images/'.$editedRecipe[$i]['img'];
                    $removedImgPath[$i]['new'] = 'images/remove-'.$editedRecipe[$i]['img'];
                    rename($removedImgPath[$i]['old'], $removedImgPath[$i]['new']);

                }

                // 旧画像ファイルの名前を新画像ファイルにつける
                $editedInfo[$i]['img'] = $editedRecipe[$i]['img'];

            }

            // アップデート処理を実行
            // UpdateSqlのインスタンスを作成
            $updateRecipe = new UpdateSql('レシピを更新', 0);
        
            // 複数のレコードを更新する
            $results = $updateRecipe->updateRecipeT($editedInfo);

            // 結果を取得
            $editResult = [];
            foreach($results as $key) {
                $result = $key->getResult();
                $editResult[] = $result;
            }

            
            for($i = 0; $i < count($editResult); $i++) {
                if($editResult[$i]['resultNo'] == 0) {
                    // エラー画面へ遷移
                    $vi = new View();
                        $vi->setAssign('title', 'ippin食材編集画面 | 食材追加処理エラー'); // タイトルバー用
                        $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                        $vi->setAssign('bodyId', 'error');  // ？
                        $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                        $vi->setAssign('resultNo', $resultObj['resultNo']);  // 処理結果No 0:エラー, 1:成功
                        $vi->setAssign('h1Title', $resultObj['resultTitle']); // エラーメッセージのタイトル
                        $vi->setAssign('resultMsg', $resultObj['resultMsg']); // エラーメッセージ
                        $vi->setAssign('linkUrl', $resultObj['linkUrl']);    // 戻るボタンに設置するリンク先
                    
                    $_SESSION['viewAry'] = $vi->getAssign();
                    $vi ->screenView('templateAdmi  n');
                    exit;

                } else {
                    // 新画像ファイルがアップロードされていれば、また、アップデートの結果をチェックし成功であれば、旧画像ファイルを削除し新画像ファイルをアップロードする
                    if(!empty($removedImgPath[$i])){
                        if($editResult[$i]['resultNo'] == 1) {
                            $fileUp = $obj->fileUplode($editedInfo[$i]['img'], $fileInfos[$i]);
                            if (checkClass($fileUp)) {
                                $resultArr = $fileUp->getResult();

                                if($resultArr['resultNo'] == 0) {
                                    // エラー画面へ遷移
                                    $vi = new View();
                                        $vi->setAssign('title', 'ippin食材編集画面 | 食材追加処理エラー'); // タイトルバー用
                                        $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                                        $vi->setAssign('bodyId', 'error');  // ？
                                        $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                                        $vi->setAssign('resultNo', $resultObj['resultNo']);  // 処理結果No 0:エラー, 1:成功
                                        $vi->setAssign('h1Title', $resultObj['resultTitle']); // エラーメッセージのタイトル
                                        $vi->setAssign('resultMsg', $resultObj['resultMsg']); // エラーメッセージ
                                        $vi->setAssign('linkUrl', $resultObj['linkUrl']);    // 戻るボタンに設置するリンク先
                    
                                    $_SESSION['viewAry'] = $vi->getAssign();
                                    $vi ->screenView('templateAdmin');
                                    exit;

                                }
                                unlink($removedImgPath[$i]['new']);
            
                            }
                        }
                    }
                }
            }
            // updateが終わったら、recipeManagementへリダイレクト
            header('Location: recipeManagement.php');
            }
            elseif ($_POST['update'] == 'cancel') {
            // 処理をせずにrecipeManagementへリダイレクト
                header('Location: recipeManagement.php');
                
            }
        }

        
        ////////// 画面出力制御処理 //////////
        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign("title", "ippin管理画面 | レシピ編集画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "recipeEdit");
        $vi->setAssign("h1Title", "レシピ編集画面");
        $vi->setAssign("main", "recipeEdit");
        $vi->setAssign("editedRecipe", $editedRecipe);
        $vi->setAssign("allFoodsList", $allFoodsList);
        $vi->setAssign("recipeIds", $recipeIds);
        $vi->setAssign("recipeInfo", $recipeInfo);
        $vi->setAssign("foodIds", $foodIds);
        $vi->setAssign("flag", $flag);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi ->screenView("templateAdmin");
    
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