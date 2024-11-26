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
            for ($i = 0; $i < count($recipeInfo); $i++) {
                for ($x = 0; $x < count($recipeIds); $x++) {
                    if ($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
                        $deleteRecipe[] = $recipeInfo[$i];
                    }
                }
            }
            // $viに値を入れていく
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
    
            // templateUserに$viを渡す
            $vi ->screenView('templateAdmin');
            exit;

        } else {
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            foreach ($_SESSION['viewAry']['deleteRecipe'] as $info) {
                $recipeInfo[$info['recipeId']] = $info;
            }
            
        }
        
        
        
        // 削除ボタンが押されたら、recipeTableを更新して処理結果画面へ遷移
        if (array_key_exists('delete', $_POST)) {
            if ($_POST['delete'] == 'delete') {
                
                $deletedRecipe = new DeleteSql('リンク切れレシピを削除', 0);
                
                // 複数のレコードを更新する
                $result = $deletedRecipe->deleteRecipeT($recipeIds);
                $resultArr = $result;
                // 失敗したらエラー画面へ遷移
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
                // $htmlText .= '<table><tr><th></th><th>レシピ名</th><th>処理結果</th></tr>'
                //     .$htmlText.'</table>';

                $vi->setAssign('title', 'ippin管理画面 | リンク切れレシピ削除結果画面'); // タイトルバー用
                $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                $vi->setAssign('bodyId', 'result');  // ？
                $vi->setAssign('main', 'result');    // テンプレート画面へインクルードするPHPファイル
                $vi->setAssign('resultNo', $checkCount);  // 処理結果No 0:エラー, 1:成功
                $vi->setAssign('h1Title', 'リンク切れレシピ削除結果'); // エラーメッセージのタイトル
                $vi->setAssign('resultMsg', $htmlText); // エラーメッセージ
                $vi->setAssign('linkUrl', 'manageTop.php');    // 戻るボタンに設置するリンク先
        
                // $viの値を$_SESSIONに渡して使えるようにする
                $_SESSION['viewAry'] = $vi->getAssign();
        
                // templateUserに$viを渡す
                $vi ->screenView('templateAdmin');
                exit;
                   
            } elseif ($_POST['delete'] == 'cancel') {
                // 処理をせずにmanageTopへリダイレクト
                header('Location: manageTop.php');
            }
        } 
    
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
echo '<pre>';

echo '$_SESSIONの配列';
print_r($_SESSION['viewAry']);
echo '<br>';
echo '$_POSTの配列';
print_r($_POST);
echo '<br>';
echo '$recipeIdsの配列';
print_r($recipeIds);
echo '<br>';
echo '$recipeInfoの配列';
print_r($recipeInfo);
echo '<br>';
echo '$deleteRecipeの配列';
print_r($deleteRecipe);
echo '<br>';

echo '</pre>';
