<?php

// セッションの開始
session_start();

// リファラチェック
// $refererUrl = '';
// $refererResult = '';

// $refererUrl = $_SERVER['HTTP_REFERER'];
// $refererResult = (preg_match('|https?://[\w]+/|',$refererUrl));

// if ($refererResult == 0) {
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
require_once 'common/InsertSql.php';
require_once 'common/SelectSql.php';
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
        // Insert(追加ボタンを押した場合の処理)
        if (isset($_POST['insert'])) {    
            // $_POSTの内容を$foodInfoに格納
            $foodInfo = [];
            $foodInfo = [
                'foodName' => e($_POST['foodName']),
                'foodCatId' => e($_POST['foodCatId'])
            ];
            
            // 追加処理
            $insertFood = new InsertSql('食材の追加処理', 9);
            $foodsList = $insertFood->insertFoodM($foodInfo);

            // $_POSTを初期化
            $_POST = [];

            // Insertに失敗したらエラー画面へ遷移
            if (checkClass($foodsList)) {
                $resultObj = $foodsList->getResult();  // 配列を取得

                if ($resultObj['resultNo'] == 0) {
                    // 失敗したらエラー画面へ遷移
                    $vi = new View();
                        $vi->setAssign('title', 'ippin管理画面 | 食材追加処理エラー'); // タイトルバー用
                        $vi->setAssign('cssPath', 'css/Admin.css');  // CSSファイルの指定
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
                    echo '<script>alert("食材の追加が完了しました！");</script>';
                    
                }
            }
        } else {
            $foodInfo = [];
    
        }

        // 食材カテゴリを取得
        $obj = new SelectSql('食材カテゴリを取得', 8);
        $foodCatM = $obj->getFoodCatM();

        // 食材カテゴリの取得に失敗したらエラー処理、成功したら次の処理を実行
        if (!isset($foodCatM)) {
            // 失敗したらエラー画面へ遷移
            $vi = new View();
                $vi->setAssign('title', 'ippin管理画面 | 食材カテゴリ取得エラー'); // タイトルバー用
                $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                $vi->setAssign('bodyId', 'error');  // ？
                $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                $vi->setAssign('resultNo', 0);  // 処理結果No 0:エラー, 1:成功
                $vi->setAssign('h1Title', '食材カテゴリ取得エラー'); // エラーメッセージのタイトル
                $vi->setAssign('resultMsg', '食材カテゴリの取得に失敗しました'); // エラーメッセージ
                $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
            
            $_SESSION['viewAry'] = $vi->getAssign();
            $vi ->screenView('templateAdmin');
            exit;

        }

        // 食材一覧を取得
        $obj = new SelectSql('食材一覧を取得', 8);
        $foodsList = $obj->getFoodList();

        // 食材一覧の取得に失敗したらエラー処理、成功したら次の処理を実行
        if (!isset($foodsList)) {
            // 失敗したらエラー画面へ遷移
            $vi = new View();
                $vi->setAssign('title', 'ippin管理画面 | 食材一覧取得エラー'); // タイトルバー用
                $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                $vi->setAssign('bodyId', 'error');  // ？
                $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                $vi->setAssign('resultNo', 0);  // 処理結果No 0:エラー, 1:成功
                $vi->setAssign('h1Title', '食材一覧取得エラー'); // エラーメッセージのタイトル
                $vi->setAssign('resultMsg', '食材一覧の取得に失敗しました'); // エラーメッセージ
                $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
            
            $_SESSION['viewAry'] = $vi->getAssign();
            $vi ->screenView('templateAdmin');
            exit;

        }
        
        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | 食材マスタ管理画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'foodsManagement');
        $vi->setAssign('h1Title', '食材マスタ管理画面');
        $vi->setAssign('main', 'foodsManagement');

        // $viにuserId, $foodCatM, $foodsListを入れる
        $vi->setAssign('userId', 1);
        $vi->setAssign('foodCatM', $foodCatM);
        $vi->setAssign('foodsList', $foodsList);

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
// print_r($_SESSION);
// echo '<br>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$foodInfoの配列';
// print_r($foodInfo);
// echo '<br>';
// echo '$foodInfoArrayの配列';
// print_r($foodInfoArray);
// echo '<br>';
// echo '$foodNameの配列';
// print_r($foodName);
// echo '<br>';
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '<br>';
// echo '$foodCatIdの配列';
// print_r($foodCatId);
// echo '<br>';

// echo '</pre>';
