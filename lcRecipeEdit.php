<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/UpdateSql.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


////////// ユーザー認証処理 //////////
// セッション情報から認証情報を取得し、権限があるかをチェック
if (isset($_SESSION['userMail']) && isset($_SESSION['userPw'])) {
    $userMail = $_SESSION['userMail'];
    $userPw = $_SESSION['userPw'];
    $userFlag = 0;
    $obj = new UserLogin('ユーザ認証処理', 6);
    
    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    // ユーザ認証OK
    if ($result) {
        $vi = new View();
        $recipeIds = [];
        $recipeInfo = [];
        $editedRecipe = [];


        // 編集画面の表示へ遷移する場合
        if (isset($_POST['choicedRecipe'])) {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeInfo = $_SESSION['viewAry']['noLinkRecipeList'];

            // 編集のチェックがついたレシピのみ配列にセット
            for ($i = 0; $i < count($recipeInfo); $i++) {
                for ($x = 0; $x < count($recipeIds); $x++) {
                    if ($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
                        $editedRecipe[] = $recipeInfo[$i];

                    }
                }
            }

            // 編集画面の値をViewインスタンスへセット
            $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ編集画面');
            $vi->setAssign('cssPath', 'css/admin.css');
            $vi->setAssign('bodyId', 'lcRecipeEdit');
            $vi->setAssign('h1Title', 'リンク切れレシピ 編集画面');
            $vi->setAssign('main', 'lcRecipeEdit');
            $vi->setAssign('editedRecipe', $editedRecipe);
            $vi->setAssign('noLinkRecipeList', $recipeInfo);
            if (isset($recipeIds)) {
                $vi->setAssign('recipeIds', $recipeIds);
            }
    
            // $viの値を$_SESSIONに渡して使えるようにする
            $_SESSION['viewAry'] = $vi->getAssign();
    
            // templateAdminに$viを渡す
            $vi ->screenView('templateAdmin');
            exit;


        } else {  //更新処理を実行する場合
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            $lastUpdate = getDatestr();
            
            // ポストされた更新情報をセット
            foreach ($_POST as $value) {
                if (gettype($value) == 'array') {
                    $recipeInfo[$value['recipeId']] = $value;
                    $recipeInfo[$value['recipeId']]['userId'] = $_SESSION['userId'];
                    $recipeInfo[$value['recipeId']]['lastUpdate'] = $lastUpdate;
                }
            }

            // 更新レコード内容をレシピIDをKeyに配列にセット
            foreach ($_SESSION['viewAry']['editedRecipe'] as $value) {
                $editedRecipe[$value['recipeId']] = $value;
            }
            
            // 更新ボタンが押されたら、recipeTableを更新して処理結果画面へ遷移
            if (array_key_exists('update', $_POST)) {
                if ($_POST['update'] == 'update') {
                    // UpdateSqlのインスタンスを作成
                    $updateRecipe = new UpdateSql('レシピのリンク情報を更新', 15);
                
                    // レコードを更新する(複数)
                    $result = $updateRecipe->updateRecipeLink($recipeInfo);

                    // 更新結果を処理結果画面用に加工
                    $resultArr = $result;
                    $checkCount = 1;
                    $htmlText = '';
                    foreach ($resultArr as $key => $value) {
                        if (checkClass($value)) {
                            $resultObj = $value->getResult();
                            if ($resultObj['resultNo'] == 0) {
                                $checkCount = 0;
                            }
                            //テーブル用データを作成
                            // $htmlText .= '<tr>
                            //     <td>' . ($resultObj['resultNo'] == 0? '失敗':'成功') . '</td>'.
                            //     '<td>' . ($editedRecipe[$key]['recipeName']) . '</td>'.
                            //     '<td>' . ($resultObj['resultMsg']) . '</td></tr>';
                            $resultTxt[] = $resultObj['resultNo'] == 0? '失敗':'成功';
                            $recipeName[] = $editedRecipe[$key]['recipeName'];
                            $resultMsg[] = $resultObj['resultMsg'];
                        }
                    }

                    // 処理結果画面の値をViewインスタンスへセット
                    $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ更新結果画面'); // タイトルバー用
                    $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                    $vi->setAssign('bodyId', 'result');  // ？
                    $vi->setAssign('main', 'result');    // テンプレート画面へインクルードするPHPファイル
                    $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                    $vi->setAssign('h1Title', 'リンク切れレシピ更新結果'); // エラーメッセージのタイトル
                    $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                    $vi->setAssign('resultTxt', $resultTxt); // 処理結果
                    $vi->setAssign('recipeName', $recipeName); // レシピ名
                    $vi->setAssign('resultMsg', $resultMsg); // エラーメッセージ
                    $vi->setAssign('linkUrl', 'manageTop.php');    // 戻るボタンに設置するリンク先
                    $vi->setAssign('noLinkRecipeList', $_SESSION['viewAry']['noLinkRecipeList']);

                    // $vi->setAssign('resultMsg', $htmlText); // エラーメッセージ

                    // $viの値を$_SESSIONに渡して使えるようにする
                    $_SESSION['viewAry'] = $vi->getAssign();
                    // templateAdminに$viを渡す
                    $vi ->screenView('templateAdmin');
                    exit;

                } elseif ($_POST['update'] == 'cancel') { //削除処理がキャンセルされた場合
                // 処理をせずにrlinkCheckへリダイレクト
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
