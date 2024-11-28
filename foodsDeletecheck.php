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

        ////////// 食材削除処理 //////////
        if (array_key_exists('delete', $_POST)) {
            if ($_POST['delete'] == 'delete') {
                $viewAry = $_SESSION['viewAry'];
                $delId = $viewAry['delId'];
                $delName = $viewAry['deleteInfo']['foodName'];
                $deletedFood = new DeleteSql('食材の削除', 9);
                
                // 食材レコードを削除する
                $delResult = $deletedFood->deleteFoodM($delId, $delName);
                // 失敗したらエラー画面へ遷移
                if (checkClass($delResult)) {
                    $resultObj = $delResult->getResult();

                    if ($resultObj['resultNo'] == 0) {
                        // エラー画面へ遷移
                        $vi = new View();
                            $vi->setAssign('title', 'ippin食材削除画面 | 食材削除処理エラー'); // タイトルバー用
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
                        // deleteが終わったら、foodsManagementへリダイレクト
                        header('Location: foodsManagement.php');
                        // for 豊田さん：JSでアラート（$resultObj['resultMsg']）出してほしい（foodsEdit.php参照）

                    }
                }   
            } elseif ($_POST['delete'] == 'cancel') {
                // 処理をせずにfoodsManagementへリダイレクト
                header('Location: foodsManagement.php');
                
            }
        } 


        ////////// 画面出力制御処理 //////////
        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $delId = $_GET['id'];
        $viewAry = $_SESSION['viewAry'];
        $foodAry = $viewAry['foodsList'];

        foreach ($foodAry as $obj) {
            if ($obj['foodId'] == $delId) { 
                $deleteInfo = $obj;
                break;
            }
        }
        
        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | 食材マスタ削除確認');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'foodsDeleteCheck');
        $vi->setAssign('h1Title', '食材マスタ削除確認');
        $vi->setAssign('main', 'foodsDeleteCheck');
        $vi->setAssign('delId', $delId);
        $vi->setAssign('foodCatM', $viewAry['foodCatM']);
        $vi->setAssign('deleteInfo', $deleteInfo);
        
        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();
        
        // templateAdminに$viを渡す
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
// print_r($_SESSION);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_GETの配列';
// print_r($_GET);
// echo '<br>';

// echo '</pre>';
