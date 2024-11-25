<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/CountSql.php';
require_once 'common/SelectSql.php';
require_once 'common/UserLogin.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


////////// ユーザー認証処理 //////////
if(!isset($_SESSION['userName'])) {
    // $_POSTの内容を変数で受ける
    $userMail = e($_POST['mailAddress']);
    $userPw = trim(e($_POST['password']));
    $userFlag = 0;
    
    // UserLoginのインスタンスを作成
    $obj = new UserLogin('ユーザ認証処理', 6);
    
    // ユーザー情報を持っているかどうかの確認
    if (isset($userMail) && strlen($userPw)) {
        // ユーザ認証を実行
        $result = $obj->getUserInfo($userMail, sha1($userPw), $userFlag);
        
        // 認証結果をチェック
        if (checkClass($result)) { 
            $resultArr = $result->getResult();  // 配列を取得

            // 失敗ならエラー画面へ遷移
            if ($resultObj['resultNo'] == 0) {
                // 処理結果を配列にセット
                $vi = new View();
                    $vi->setAssign('title', 'ippin管理画面 | ログインエラー'); // タイトルバー用
                    $vi->setAssign('cssPath', 'css/user.css');  // CSSファイルの指定
                    $vi->setAssign('bodyId', 'error');  // ？
                    $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                    $vi->setAssign('resultNo', $resultArr['resultNo']);  // 処理結果No 0:エラー, 1:成功
                    $vi->setAssign('h1Title', $resultArr['resultTitle']); // エラーメッセージのタイトル
                    $vi->setAssign('resultMsg', $resultArr['resultMsg']); // エラーメッセージ
                    $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
                    
                $_SESSION['viewAry'] = $vi->getAssign();
                $vi ->screenView('templateUser');
                exit;

            }
        } else {
            // セッションにログイン情報をセット
            $_SESSION['userMail'] = $userMail;
            $_SESSION['userPw'] = $userPw;
            $_SESSION['userName'] = $result['nickName'];
            $_SESSION['userFlag'] = $userFlag;
            $_SESSION['userId'] = $result['userId'];

        }
    } else {
        $vi = $obj->getLoginErrView();
        $_SESSION['viewAry'] = $vi->getAssign();
        $vi->screenView('templateUser');
        exit;

    }
}


////////// 画面出力制御処理 //////////
// CountSqlSqlのインスタンスを作成
$CountSql = new CountSql('レシピ件数の取得', 8);

// レシピ件数を取得
$countRecipeAll = $CountSql->getCount('##', 9);
$countRecipeOn = $CountSql->getCount('##', 1);
$countRecipeOff = $CountSql->getCount('##', 0);

// レシピ件数の取得に失敗したらエラー処理、成功したら次の処理を実行
if (!isset($countRecipeAll) || !isset($countRecipeOn) || !isset($countRecipeOff)) {
    // 失敗したらエラー画面へ遷移
    $vi = new View();
        $vi->setAssign('title', 'ippin管理画面 | レシピ件数取得エラー'); // タイトルバー用
        $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
        $vi->setAssign('bodyId', 'error');  // ？
        $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
        $vi->setAssign('resultNo', 0);  // 処理結果No 0:エラー, 1:成功
        $vi->setAssign('h1Title', 'レシピ件数取得エラー'); // エラーメッセージのタイトル
        $vi->setAssign('resultMsg', 'レシピ件数の取得に失敗しました'); // エラーメッセージ
        $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
        
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi ->screenView('templateAdmin');
    exit;

} else {
    // SelectSqlのインスタンスを作成
    $selectSql = new SelectSql('食材選択肢の取得', 8);
    
    // foodListを取得
    $foodsList = $selectSql->getFood();
    
    // $foodsListの取得に失敗したらエラー処理、成功したら次の処理を実行
    if (checkClass($foodsList)) {
        $resultArr = $result->getResult();  // 配列を取得

        if ($resultObj['resultNo'] == 0) {
            // 失敗したらエラー画面へ遷移
            $vi = new View();
            $vi->setAssign('title', 'ippin管理画面 | 食材リスト取得エラー'); // タイトルバー用
            $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
            $vi->setAssign('bodyId', 'error');  // ？
            $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
            $vi->setAssign('resultNo', $resultArr['resultNo']);  // 処理結果No 0:エラー, 1:成功
            $vi->setAssign('h1Title', $resultArr['resultTitle']); // エラーメッセージのタイトル
            $vi->setAssign('resultMsg', $resultArr['resultMsg']); // エラーメッセージ
            $vi->setAssign('linkUrl', $resultArr['linkUrl']);    // 戻るボタンに設置するリンク先
            
            $_SESSION['viewAry'] = $vi->getAssign();
            $vi ->screenView('templateAdmin');
            exit;

        }
    } else {
        // viewクラスの呼び出し
        $vi = new View();
        
        // $viに値を入れていく
        $vi->setAssign('title', 'ippin管理画面 | 管理者トップ画面');
        $vi->setAssign('cssPath', 'css/admin.css');
        $vi->setAssign('bodyId', 'manageTop');
        $vi->setAssign('h1Title', '管理者トップ画面');
        $vi->setAssign('main', 'manageTop');
        
        // $viに$countRecipeAll, $countRecipeOn, $countRecipeOffを入れる
        $vi->setAssign('countRecipeAll', $countRecipeAll);
        $vi->setAssign('countRecipeOn', $countRecipeOn);
        $vi->setAssign('countRecipeOff', $countRecipeOff);
        
        // 取得したfoodsListを渡す
        $vi->setAssign('foodsList', $foodsList);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();
        
        // templateUserに$viを渡す
        $vi ->screenView('templateAdmin');
    }
}


// デバッグ用※あとで消そうね！
// echo '<pre>';
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';
// echo '$countRecipeAll:';
// print_r($countRecipeAll);
// echo '<br>';
// echo '$countRecipeOn:';
// print_r($countRecipeOn);
// echo '<br>';
// echo '$countRecipeOff:';
// print_r($countRecipeOff);
// echo '<br>';
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '<br>';
// echo '</pre>';
