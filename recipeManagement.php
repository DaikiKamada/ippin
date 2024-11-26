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


////////// ユーザー認証処理 //////////
// セッション情報から認証情報を取得し、権限があるかをチェック
$userMail = $_SESSION['userMail'];
$userPw = $_SESSION['userPw'];
$userFlag = 0;
$obj = new UserLogin('ユーザ認証処理', 6);

if (isset($userMail) && isset($userPw)) {
    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);

    ////////// insert(追加ボタンを押した場合の処理) /////////
    if (isset($_POST['insert'])) {

        // POSTとFILESの内容を$resipeInfoにコピー
        $recipeInfo = $_POST;
        $FileInfo = $_FILES;

        $fileCheckObj = new ImgFile('画像ファイル処理', 0);
        $NewRecipeId = $fileCheckObj->getNewRecipeId();
        $fileCheck = $fileCheckObj->checkUplodeFile($NewRecipeId, $FileInfo);

        $recipeInfo['foodIds'] = $_SESSION['viewAry']['foodIds'];
        $recipeInfo['userId'] = $_SESSION['userId'];
        $recipeInfo['img'] = $fileCheck;

        if (checkClass($fileCheck)) {
            $resultArr = $fileCheck->getResult();  // 配列を取得

            if ($resultObj['resultNo'] == 0) {
                // 失敗したらエラー画面へ遷移
                $vi = new View();
                $vi->setAssign('title', 'ippin管理画面 | 画像ファイルの処理エラー'); // タイトルバー用
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
            // インサート OR アップデート処理を実行
            // 日時を取得して配列に追加
            $lastUpdate = getDatestr();
            $recipeInfo['lastUpdate'] = $lastUpdate;
            // foodIdsをソート
            $foodValues = sortFoodIds($recipeInfo['foodIds']);
            $recipeInfo['foodValues'] = $foodValues;

            // 追加処理
            $obj = new InsertSql('レシピの追加処理', 0);
            $recipeList = $obj->insertRecipeT($recipeInfo);

            // $_POSTを初期化
            $_POST = array();

            if (checkClass($recipeList)) {
                $resultArr = $recipeList->getResult();  // 配列を取得
                if ($resultArr['resultNo'] == 0) {
                    // 失敗したらエラー画面へ遷移
                    $vi = new View();
                    $vi->setAssign('title', 'ippin管理画面 | 画像の追加処理エラー'); // タイトルバー用
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
                        //エラー画面に遷移？
                        $resultArr = $fileUp->getResult();

                    }
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
        $foodIds = $_POST['selectFoods'];
        $flag = $_POST['flag'];

    }

    // foodIdsをソート
    $fValueStr = sortFoodIds($foodIds);

    // SelectSqlで食材を取得
    $obj = new SelectSql('レシピ一覧を取得', 0);
    $foodsList = $obj->getSelectedFood($foodIds);

    // 食材がなかった場合の処理
    if (checkClass($foodsList)) {
        ///////////////////////////////// true : エラー処理する /////////////////////////////////
        echo '<p>たいへん！食材がうまく取得できないよ！管理人を呼んでね！</p>';
    }

    // SelectSqlでレシピ一覧を取得
    $recipeList = $obj->getRecipe($fValueStr, $flag);

    if (checkClass($recipeList)) {
        // レシピがなかった場合の処理
        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "recipeManagement");
        $vi->setAssign("h1Title", "レシピテーブル管理画面");
        $vi->setAssign("main", "recipeManagement");
        $vi->setAssign("foodIds", $foodIds);
        $vi->setAssign("flag", $flag);
        $vi->setAssign("foodsList", $foodsList);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi->screenView("templateAdmin");

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

        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign("title", "ippin管理画面 | レシピテーブル管理画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "recipeManagement");
        $vi->setAssign("h1Title", "レシピテーブル管理画面");
        $vi->setAssign("main", "recipeManagement");
        $vi->setAssign("foodIds", $foodIds);
        $vi->setAssign("flag", $flag);
        $vi->setAssign("recipeList", $recipeList);
        $vi->setAssign("foodsList", $foodsList);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi->screenView("templateAdmin");
    }

} else {
    $vi = $obj->getLoginErrView();
    $_SESSION['viewAry'] = $vi->getAssign();
    $vi->screenView('templateAdmin');

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
