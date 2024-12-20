<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once "common/InsertSql.php";
require_once 'common/ImgFile.php';
require_once "common/SelectSql.php";
require_once "common/UserLogin.php";
require_once 'common/Utilities.php';
require_once 'view/View.php';

// リファラチェック（AWS環境でのみONにしよう！）
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


// 出力制御のオブジェクトを作成
$vi = new View();

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
        ////////// insert(追加ボタンを押した場合の処理) /////////
        if (isset($_POST['insert'])) {
            // POSTとFILESの内容を$resipeInfoにコピー
            $recipeInfo = $_POST;
            $FileInfo = $_FILES;

            $fileCheckObj = new ImgFile('画像ファイル処理', 8);
            $NewRecipeId = $fileCheckObj->getNewRecipeId();
            $fileCheck = $fileCheckObj->checkUplodeFile($NewRecipeId, $FileInfo, 0);

            $recipeInfo['foodIds'] = $_SESSION['viewAry']['foodIds'];
            $recipeInfo['userId'] = $_SESSION['userId'];
            $recipeInfo['img'] = $fileCheck;

            if (checkClass($fileCheck)) {
                $resultObj = $fileCheck->getResult();  // 配列を取得

                if ($resultObj['resultNo'] == 0) {
                    // 失敗したらエラー画面へ遷移
                    $vi->setAssign('title', 'ippin管理画面 | レシピ追加処理結果画面'); // タイトルバー用
                    $vi->setAssign('cssPath', 'css/admin.css');  // CSSファイルの指定
                    $vi->setAssign('bodyId', 'error');  // ？
                    $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                    $vi->setAssign('resultNo', $resultObj['resultNo']);  // 処理結果No 0:エラー, 1:成功
                    $vi->setAssign('h1Title', $resultObj['resultTitle']); // エラーメッセージのタイトル
                    $vi->setAssign('resultMsg', $resultObj['resultMsg']); // エラーメッセージ
                    $vi->setAssign('linkUrl', $resultObj['linkUrl']);    // 戻るボタンに設置するリンク先

                    $_SESSION['viewAry'] = $vi->getAssign();
                    $vi->screenView('templateAdmin');
                    exit;

                }

            } else {
                $img = $fileCheck;
                // インサート処理を実行
                // 日時を取得して配列に追加
                $lastUpdate = getDatestr();
                $recipeInfo['lastUpdate'] = $lastUpdate;
                // foodIdsをソート
                $foodValues = sortFoodIds($recipeInfo['foodIds']);
                $recipeInfo['foodValues'] = $foodValues;

                // 追加処理
                $obj = new InsertSql('レシピの追加処理', 8);
                $recipeList = $obj->insertRecipeT($recipeInfo);

                // $_POSTを初期化
                $_POST = array();

                if (checkClass($recipeList)) {
                    $resultObj = $recipeList->getResult();  // 配列を取得
                    if ($resultObj['resultNo'] == 0) {
                        // 失敗したらエラー画面へ遷移
                        $vi->setAssign('title', 'ippin管理画面 | レシピ追加処理結果画面'); // タイトルバー用
                        $vi->setAssign('cssPath', 'css/Admin.css');  // CSSファイルの指定
                        $vi->setAssign('bodyId', 'error');  // ？
                        $vi->setAssign('main', 'error');    // テンプレート画面へインクルードするPHPファイル
                        $vi->setAssign('resultNo', $resultObj['resultNo']);  // 処理結果No 0:エラー, 1:成功
                        $vi->setAssign('h1Title', $resultObj['resultTitle']); // エラーメッセージのタイトル
                        $vi->setAssign('resultMsg', $resultObj['resultMsg']); // エラーメッセージ
                        $vi->setAssign('linkUrl', $resultObj['linkUrl']);    // 戻るボタンに設置するリンク先

                        $_SESSION['viewAry'] = $vi->getAssign();
                        $vi->screenView('templateAdmin');
                        exit;

                    } else {
                        // SQLが正常実行の場合、画像ファイルをアップロード
                        // エラーの場合、ファイルの修正を促すメッセージを表示
                        $fileUp = $fileCheckObj->fileUplode($img, $FileInfo);

                        if (checkClass($fileUp)) {
                            $resultObj = $fileUp->getResult();

                            if ($resultObj['resultNo'] == 0) {
                                // 失敗したらエラー画面へ遷移
                                    $vi->setAssign('title', 'ippin管理画面 | レシピ追加処理結果画面'); // タイトルバー用
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
            
                            }
                        }
                        echo '<script>
                        alert("追加登録が完了しました！");
                        window.location.href = "recipeManagement.php";
                        </script>';
                    }
                }
            }
        } else {
            $recipeInfo = [];

        }


        // ////////// selectで食材・レシピ一覧を取得 //////////
        $foodIds = [];

        // SESSIONの['viewAry']に['foodIds']と['flag']があればコピーし、なければPOSTの中身をコピーする
        if (array_key_exists('foodIds', $_SESSION['viewAry']) && array_key_exists('flag', $_SESSION['viewAry'])) {
            $foodIds = $_SESSION['viewAry']['foodIds'];
            $flag = $_SESSION['viewAry']['flag'];

        } else {
            if (isset($_POST['selectFoods'])) {
                $foodIds = $_POST['selectFoods'];
            } else {
            $foodIds = [];
            }              
            $flag = $_POST['flag'];

        }

        // foodIdsをソート
        $fValueStr = sortFoodIds($foodIds);

        // SelectSqlで食材を取得
        $obj = new SelectSql('食材一覧を取得', 8);
        $foodsList = $obj->getSelectedFood($foodIds);

        // 食材がなかった場合の処理
        if (checkClass($foodsList)) {
            // 失敗したらエラー画面へ遷移
                $vi->setAssign('title', 'ippin管理画面 | 食材リスト取得エラー'); // タイトルバー用
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

        }

        // SelectSqlで調理方法を取得
        $obj = new SelectSql('調理方法の一覧を取得', 8);
        $howToList = $obj->getHowToCatM();

        // 調理方法がなかった場合の処理
        if (checkClass($howToList)) {
            $resultObj = $howToList->getResult();
            // 失敗したらエラー画面へ遷移
                $vi->setAssign('title', 'ippin管理画面 | 調理方法リスト取得エラー'); // タイトルバー用
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

        }

        // SelectSqlでレシピ一覧を取得
        $recipeList = $obj->getRecipe($fValueStr, $flag);

        if (checkClass($recipeList)) {
            // レシピがなかった場合の処理
            $vi->setAssign("foodIds", $foodIds);
            $vi->setAssign("flag", $flag);
            $vi->setAssign("foodsList", $foodsList);
            $vi->setAssign("howToList", $howToList);

        } else {
            // レシピがあった場合の処理
            // // recipeFlagを上書きする(0を非表示に、1を表示に、そのほかの場合は不明に)
            for ($i = 0; $i < count($recipeList); $i++) {
                if ($recipeList[$i]['recipeFlag'] == 0) {
                    $recipeList[$i]['recipeFlag'] = '非表示';

                } elseif ($recipeList[$i]['recipeFlag'] == 1) {
                    $recipeList[$i]['recipeFlag'] = '表示';

                } else {
                    $recipeList[$i]['recipeFlag'] = '不明';

                }
            }
            // $viに値を入れていく
            $vi->setAssign("foodIds", $foodIds);
            $vi->setAssign("flag", $flag);
            $vi->setAssign("recipeList", $recipeList);
            $vi->setAssign("foodsList", $foodsList);
            $vi->setAssign("howToList", $howToList);

        }
        $vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "recipeManagement");
        $vi->setAssign("h1Title", "レシピテーブル管理画面");
        $vi->setAssign("main", "recipeManagement");
        

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi->screenView("templateAdmin");
        
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

// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '<br>';

// echo '</pre>';
