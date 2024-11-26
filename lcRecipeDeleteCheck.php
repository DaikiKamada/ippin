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
if (isset($_SESSION['userMail']) && isset($_SESSION['userPw'])) {
    // ユーザ認証を実行
    $userMail = $_SESSION['userMail'];
    $userPw = $_SESSION['userPw'];
    $userFlag = 0;
    $obj = new UserLogin('ユーザ認証処理', 0);
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    // ユーザ認証OK
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
            // 確認画面の値をViewインスタンへセット
            $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ削除確認画面');
            $vi->setAssign('cssPath', 'css/admin.css');
            $vi->setAssign('bodyId', 'lcRecipeDeleteCheck');
            $vi->setAssign('h1Title', 'リンク切れレシピ 削除確認画面');
            $vi->setAssign('main', 'lcRecipeDeleteCheck');
            $vi->setAssign('deleteRecipe', $deleteRecipe);
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
                    $deletedRecipe = new DeleteSql('リンク切れレシピを削除', 0);
                    $result = $deletedRecipe->deleteRecipeT($recipeIds);

                    // 削除結果を処理結果画面用に加工
                    $resultArr = $result;
                    $checkCount = 1;
                    $htmlText = '';
                    foreach ($resultArr as $key => $value) {
                        if (checkClass($value)) {
                            $resultObj = $value->getResult();
                            if ($resultObj['resultNo'] == 0) {
                                $checkCount = 0;
                            }
                            //テーブルデータを作成
                            $htmlText .= '<tr>
                                <td>' . ($resultObj['resultNo'] == 0? '失敗':'成功') . '</td>'.
                                '<td>' . ($recipeInfo[$key]['recipeName']) . '</td>'.
                                '<td>' . ($resultObj['resultMsg']) . '</td></tr>';
                        }
                    }
                    // 処理結果画面の値をViewインスタンへセット
                    $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ削除結果画面'); // タイトルバー用
                    $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                    $vi->setAssign('bodyId', 'error');  // ？
                    $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                    $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                    $vi->setAssign('h1Title', 'リンク切れレシピ削除結果'); // エラーメッセージのタイトル
                    $vi->setAssign('resultMsg', $htmlText); // エラーメッセージ
                    $vi->setAssign('linkUrl', 'foodsManagement.php');    // 戻るボタンに設置するリンク先
            
                    // $viの値を$_SESSIONに渡して使えるようにする
                    $_SESSION['viewAry'] = $vi->getAssign();
                    // templateUserに$viを渡す
                    $vi ->screenView('templateAdmin');
                    exit;
                    
                } elseif ($_POST['delete'] == 'cancel') { //削除処理がキャンセルされた場合
                    // 処理をせずにrecipeManagementへリダイレクト
                    header('Location: recipeManagement.php');
                }
            } 
        }

    } else { //ユーザ認証NGの場合、エラー画面へ遷移
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView('templateAdmin');
    
    }

} else { //ユーザ認証用のセッション情報がない場合、エラー画面へ遷移
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateAdmin');

}

