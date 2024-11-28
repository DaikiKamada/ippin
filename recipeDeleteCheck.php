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
        $vi = new View();
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
        else {
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
                
                $deletedRecipe = new DeleteSql('レシピの削除', 12);
                
                // 複数のレコードを更新する
                $result = $deletedRecipe->deleteRecipeT($deleteRecipeIds);
                
                // 削除結果を処理結果画面用に加工
                $resultArr = $result;
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
                    }
                }
                for($i = 0; $i < count($resultArr); $i++) {
                    //テーブルデータを作成
                    $resultTxt[] = $resultObj['resultNo'] == 0? '失敗':'成功';
                    $recipeName[] = $recipeInfo[$i]['recipeName'];
                    $resultMsg[] = $resultObj['resultMsg'];
                    // $htmlText .= '<tr><td>' . ($resultObj['resultNo'] == 0? '失敗':'成功') . '</td>'.'<td>' . ($recipeInfo[$key]['recipeName']) . '</td>'.'<td>' . ($resultObj['resultMsg']) . '</td></tr>';       
                }

                // 処理結果画面の値をViewインスタンスへセット
                $vi->setAssign('title', 'ippin管理画面 | レシピ削除処理結果画面'); // タイトルバー用
                $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                $vi->setAssign('bodyId', 'result');  // ？
                $vi->setAssign('main', 'result');    // テンプレート画面へインクルードするPHPファイル
                $vi->setAssign('h1Title', '結果'); // エラーメッセージのタイトル
                $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                $vi->setAssign('resultTxt', $resultTxt); // 処理結果
                $vi->setAssign('recipeName', $recipeName); // レシピ名
                $vi->setAssign('resultMsg', $resultMsg); // エラーメッセージ
                $vi->setAssign('linkUrl', 'manageTop.php');    // 戻るボタンに設置するリンク先
        
                // $viの値を$_SESSIONに渡して使えるようにする
                $_SESSION['viewAry'] = $vi->getAssign();
                
                // templateAdminに$viを渡す
                $vi ->screenView('templateAdmin');
                exit;

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
        $vi->setAssign('recipeList', $recipeInfo);
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
