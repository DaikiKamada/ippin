<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/UserLogin.php';
require_once 'common/UpdateSql.php';
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
        ////////// 編集ボタンが押された時の処理 //////////
        // 編集ボタンが押されたら、foodTableを更新してfoodsManagementに戻る
        if(array_key_exists('update', $_POST)) {
            if($_POST['update'] == 'update') {
                $viewAry = $_SESSION['viewAry'];
                $editId = $viewAry['editId'];
                $editName = $_POST['foodName'];
                $editCatId = $_POST['foodCatId'];

                // UpdateSqlのインスタンスを作成
                $updateFood = new UpdateSql('食材の更新', 9);

                // 食材レコードを更新する
                $updateResult = $updateFood->updateFoodM($editId, $editName, $editCatId);

                // Updateに失敗したらエラー画面へ遷移
                if (checkClass($updateResult)) {
                    $resultObj = $updateResult->getResult();
                    if ($resultObj['resultNo'] == 0) {
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

                    } else {
                        // updateが終わったら、foodsManagementへリダイレクト
                        header('Location: foodsManagement.php');

                    }
                }    
                // // 配列を用意
                // $editedInfo = [];

                // // $_SESSIONのeditInfoの情報を$editInfoに代入
                // $editedInfo = [
                //     $_SESSION['viewAry']['editInfo']['foodId'],
                //     $_POST['foodName'],
                //     $_POST['foodCatId']
                // ];
                
                // // UpdateSqlのインスタンスを作成
                // $updateFood = new UpdateSql('食材を更新', 0);

                // // 食材レコードを更新する
                // $result = $updateFood->updateFoodM($editedInfo[0], $editedInfo[1], $editedInfo[2]);

                // // 処理結果に応じての処理ができたらいいな～

                // // updateが終わったら、foodsManagementへリダイレクト
                // header('Location: foodsManagement.php');
                    
            } elseif ($_POST['update'] == 'cancel') {
                // 処理をせずにrecipeManagementへリダイレクト
                header('Location: foodsManagement.php');

            }
        }


        ////////// 画面出力制御処理 //////////
        // viewクラスの呼び出し
        $vi = new View();
        
        // $viに値を入れていく
        $editId = $_GET['id'];
        $viewAry = $_SESSION['viewAry'];
        $foodAry = $viewAry['foodsList'];

        foreach ($foodAry as $obj) {
            // $foodId = $obj['foodId'];
            if ($obj['foodId'] == $editId) {
                $editInfo = $obj;
                break;
            }
        }

        $vi->setAssign('title', 'ippin管理画面 | 食材マスタ編集画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'foodsEdit');
        $vi->setAssign('h1Title', '食材マスタ編集画面');
        $vi->setAssign('main', 'foodsEdit');
        $vi->setAssign('editId', $editId);
        $vi->setAssign('foodCatM', $viewAry['foodCatM']);
        $vi->setAssign('editInfo', $editInfo);
        
        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();
        
        // templateUserに$viを渡す
        $vi ->screenView('templateAdmin');
    
    } else {
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView('templateAdmin');
    
    }

} else {
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateAdmin');

}

// デバッグ用※あとで消そうね！
// echo '<pre>';

// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_GETの配列';
// print_r($_GET);
// echo '<br>';
// echo '$editedInfoの配列';
// print_r($editedInfo);
// echo '<br>';

// echo '</pre>';
