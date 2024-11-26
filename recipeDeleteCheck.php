<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/DeleteSql.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


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
        ////////// 画面出力制御処理 //////////
        // $_POST・$_SESSIONを受ける準備・初期化
        $recipeIds = [];
        $recipeInfo = [];
        $foodIds = [];
        
        // $_POST・$_SESSIONを変数に格納
        $foodIds = $_SESSION['viewAry']['foodIds'];
        $flag = $_SESSION['viewAry']['flag'];
        
        if (isset($_POST['choicedRecipe'])) {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeInfo = $_SESSION['viewAry']['recipeList'];
            
        }
        
        if (isset($_SESSION['viewAry']['recipeIds'])) {
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            
        }
        
        if(isset($_SESSION['viewAry']['foodsList'])){
            $foodsList = $_SESSION['viewAry']['foodsList'];
        }

        // 配列を用意(削除したいレシピを入れる)
        $deleteRecipe = [];
        $deleteRecipeIds = $recipeIds;

        // 削除したいレシピの一覧を取得
        for ($i = 0; $i < count($recipeInfo); $i++) {
            for ($x = 0; $x < count($recipeIds); $x++) {
                if ($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
                    $deleteRecipe[] = $recipeInfo[$i];

                }
            }
        }
        
        ////////// レシピ削除処理 //////////
        // 削除ボタンが押されたら、recipeTableを更新してrecipeManagementに戻る
        if (array_key_exists('delete', $_POST)) {
            if ($_POST['delete'] == 'delete') {
                
                $deletedRecipe = new DeleteSql('レシピの削除', 0);
                
                // 複数のレコードを更新する
                $result = $deletedRecipe->deleteRecipeT($deleteRecipeIds);
                
                // 失敗したらエラー画面へ遷移
                if (checkClass($delResult)) {
                    $resultObj = $delResult->getResult();

                    if ($resultObj['resultNo'] == 0) {
                        // エラー画面へ遷移
                        $vi = new View();
                            $vi->setAssign('title', 'ippin食材削除画面 | レシピ削除処理エラー'); // タイトルバー用
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

                    } else {
                        // deleteが終わったら、recipeManagementへリダイレクト
                        header('Location: recipeManagement.php');
                        // for 豊田さん：JSでアラート（$resultObj['resultMsg']）出してほしい（foodsEdit.php参照）

                    }
                }
            } elseif ($_POST['delete'] == 'cancel') {
                // 処理をせずにrecipeManagementへリダイレクト
                header('Location: recipeManagement.php');

            }
        } 

        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | レシピ削除確認画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'recipeDeleteCheck');
        $vi->setAssign('h1Title', 'recipe削除確認画面');
        $vi->setAssign('main', 'recipeDeleteCheck');
        $vi->setAssign('deleteRecipe', $deleteRecipe);
        $vi->setAssign('foodIds', $foodIds);
        $vi->setAssign('flag', $flag);
        $vi->setAssign('foodsList', $foodsList);
        if (isset($recipeIds)) {
            $vi->setAssign('recipeIds', $recipeIds);

        }

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

// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$recipeIdsの配列';
// print_r($recipeIds);
// echo '<br>';
// echo '$recipeInfoの配列';
// print_r($recipeInfo);
// echo '<br>';
// echo '$deleteRecipeの配列';
// print_r($deleteRecipe);
// echo '<br>';

// echo '</pre>';
