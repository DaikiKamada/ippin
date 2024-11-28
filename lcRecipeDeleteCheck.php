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
        $vi = new View();
        $recipeIds = [];
        $recipeInfo = [];
        $deleteRecipe = [];

        // 確認画面の表示へ遷移する場合
        if (isset($_POST['choicedRecipe'])) {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeInfo = $_SESSION['viewAry']['noLinkRecipeList'];
            
            // 削除のチェックがついたレシピのみ配列にセット
            for ($i = 0; $i < count($recipeInfo); $i++) {
                for ($x = 0; $x < count($recipeIds); $x++) {
                    if ($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
                        $deleteRecipe[] = $recipeInfo[$i];

                    }
                }
            }

            // 確認画面の値をViewインスタンスへセット
            $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ削除確認画面');
            $vi->setAssign('cssPath', 'css/admin.css');
            $vi->setAssign('bodyId', 'lcRecipeDeleteCheck');
            $vi->setAssign('h1Title', 'リンク切れレシピ 削除確認画面');
            $vi->setAssign('main', 'lcRecipeDeleteCheck');
            $vi->setAssign('deleteRecipe', $deleteRecipe);
            $vi->setAssign('noLinkRecipeList', $recipeInfo);
            if (isset($recipeIds)) {
                $vi->setAssign('recipeIds', $recipeIds);
            }
    
            // $viの値を$_SESSIONに渡して使えるようにする
            $_SESSION['viewAry'] = $vi->getAssign();
    
            // templateAdminに$viを渡す
            $vi ->screenView('templateAdmin');
            exit;

        } else { //削除処理を実行する場合
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            foreach ($_SESSION['viewAry']['deleteRecipe'] as $info) {
                $recipeInfo[$info['recipeId']] = $info;

            }

            // 削除ボタンが押されたら、recipeTableを更新して処理結果画面へ遷移
            if (array_key_exists('delete', $_POST)) {
                if ($_POST['delete'] == 'delete') {
                    // レコードを削除する（複数）
                    $deletedRecipe = new DeleteSql('リンク切れレシピを削除', 8);
                    $result = $deletedRecipe->deleteRecipeT($recipeIds);

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

                            //テーブルデータを作成
                            $resultTxt[] = $resultObj['resultNo'] == 0? '失敗':'成功';
                            $recipeName[] = $recipeInfo[$key]['recipeName'];
                            $resultMsg[] = $resultObj['resultMsg'];
                            // $htmlText .= '<tr><td>' . ($resultObj['resultNo'] == 0? '失敗':'成功') . '</td>'.'<td>' . ($recipeInfo[$key]['recipeName']) . '</td>'.'<td>' . ($resultObj['resultMsg']) . '</td></tr>';

                        }
                    }

                    // 処理結果画面の値をViewインスタンスへセット
                    $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ削除結果画面'); // タイトルバー用
                    $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                    $vi->setAssign('bodyId', 'result');  // ？
                    $vi->setAssign('main', 'result');    // テンプレート画面へインクルードするPHPファイル
                    $vi->setAssign('h1Title', 'リンク切れレシピ削除結果'); // エラーメッセージのタイトル
                    $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                    $vi->setAssign('resultTxt', $resultTxt); // 処理結果
                    $vi->setAssign('recipeName', $recipeName); // レシピ名
                    $vi->setAssign('resultMsg', $resultMsg); // エラーメッセージ
                    $vi->setAssign('linkUrl', 'linkCheck.php');    // 戻るボタンに設置するリンク先
                    $vi->setAssign('noLinkRecipeList', $_SESSION['viewAry']['noLinkRecipeList']);
            
                    // $viの値を$_SESSIONに渡して使えるようにする
                    $_SESSION['viewAry'] = $vi->getAssign();
                    
                    // templateAdminに$viを渡す
                    $vi ->screenView('templateAdmin');
                    exit;
                    
                } elseif ($_POST['delete'] == 'cancel') { //削除処理がキャンセルされた場合
                    // 処理をせずにmanageTop.phpへリダイレクト
                    header('Location: linkCheck.php');

                }
            } 
        }
    } else { //ユーザ認証NGの場合、エラー画面へ遷移
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView('templateUser');
    
    }

} else { //ユーザ認証用のセッション情報がない場合、エラー画面へ遷移
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateUser');

}
