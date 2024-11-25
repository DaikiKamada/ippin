<?php

// セッションの開始
session_start();

// ファイルのインクルード
require_once 'common/SelectSql.php';
require_once 'common/UpdateSql.php';
require_once 'common/UserLogin.php';
require_once 'common/ImgFile.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';


////////// ユーザー認証処理 //////////
// セッション情報から認証情報を取得し、権限があるかをチェック
$userMail = $_SESSION['userMail'];
$userPw = $_SESSION['userPw'];
$userFlag = 0;
$obj = new UserLogin('ユーザ認証処理', 0);

if (isset($userMail) && isset($userPw)) {
    // ユーザ認証を実行
    $result = $obj->checkUserInfo($userMail, sha1($userPw), $userFlag);
    
    if ($result) {
        // 編集ボタンが押されたら、recipteTableを更新してrecipeManagementに戻る
        if (array_key_exists('update', $_POST)) {
            if ($_POST['update'] == 'update') {

                $editedRecipe = $_SESSION['viewAry']['editedRecipe'];

                // 配列を用意
                $editedInfo = [];

                // POSTの内容をコピー
                $copyPost = $_POST;

                // 画像のアップロードの下処理
                $obj = new ImgFile('画像ファイル処理', 0);

                // $editedInfoの中身を成形
                for($i = 0; $i < count($editedRecipe); $i++) {
                    // $copyPostの、キーが数字の部分だけをコピー
                    $editedInfo[$i] = $copyPost[$i];
                    
                    // SESSIONから、userIdをコピー
                    $editedInfo[$i]['userId'] = $_SESSION['userId'];
                    
                    // 日付を取得して配列に追加
                    $lastUpdate = getDatestr();
                    $editedInfo[$i]['lastUpdate'] = $lastUpdate;
                    
                    // foodIdをソートして配列に追加
                    $foodValues = sortFoodIds($editedInfo[$i]['foodValues']);
                    $editedInfo[$i]['foodValues'] = $foodValues;
                    
                    // imgを作成
                    $NewRecipeId[$i] = $obj->getNewRecipeId();
                    $fileCheck[$i] = $obj->checkUplodeFile($NewRecipeId[$i]);
                    $img[$i] = $fileCheck[$i];

                    // 作成した$imgを配列に追加
                    if ($img[$i] != '') {
                        $editedInfo[$i]['img'] = $img[$i];
                    }
                    else {
                        $editedInfo[$i]['img'] = $editedRecipe[$i]['img'];
                    }

                    if (checkClass($fileCheck)) { 
                        //エラー画面に遷移？
                        $resultArr = $fileCheck->getResult(); 
                    } else {
                        $img = $fileCheck;
                        }
                }
                    
                // アップデート処理を実行
                // UpdateSqlのインスタンスを作成
                $updateRecipe = new UpdateSql('レシピを更新', 0);
            
                // 複数のレコードを更新する
                $results = $updateRecipe->updateRecipeT($editedInfo);

                // 結果を取得
                $editResult = [];
                foreach($results as $key) {
                    $result = $key->getResult();
                    $editResult[] = $result;
                }

                // 結果をチェックして、成功であればファイルをアップロードする
                for($i = 0; $i < count($editResult); $i++) {
                    if($editResult[$i]['resultNo'] == 1) {
                        $fileUp = $obj->fileUplode($img[$i]);
                        if (checkClass($fileUp)) {
                            //エラー画面に遷移？
                            $resultArr = $fileUp->getResult(); 
                        }
                    }
                    else {
                        print 'だめ';
                    }
                }

                // updateが終わったら、recipeManagementへリダイレクト
                header('Location: recipeManagement.php');
            }
            elseif ($_POST['update'] == 'cancel') {
            // 処理をせずにrecipeManagementへリダイレクト
                header('Location: recipeManagement.php');
            }
        }
    }


        ////////// 画面出力制御処理 //////////
        // SESSIONにeditedRecipeキーが存在すればそれをコピー、なければPOSTの値をコピー ※ページをリロードしても大丈夫なように
        if (array_key_exists('editedRecipe', $_SESSION['viewAry'])) {
            $recipeIds = $_SESSION['viewAry']['recipeIds'];
            $recipeInfo = $_SESSION['viewAry']['recipeInfo'];

        } else {
            $recipeIds = $_POST['choicedRecipe'];
            $recipeInfo = $_SESSION['viewAry']['recipeList'];

        }

        // 全ての食材を取得
        $selectFoods = new SelectSql('全ての食材を取得', 0);
        $allFoodsList = $selectFoods->getFood();

        // 配列を用意(編集したいレシピを入れる)
        $editedRecipe = [];

        // 編集したいレシピの一覧を取得
        for ($i = 0; $i < count($recipeInfo); $i++) {
            for ($x = 0; $x < count($recipeIds); $x++) {
                if ($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
                    $editedRecipe[] = $recipeInfo[$i];

                }
            }
        }
        
        // recipeManagement.phpで選択した食材、レシピフラグをSESSIONに渡すために、変数にコピー
        $foodIds = $_SESSION['viewAry']['foodIds'];
        $flag = $_SESSION['viewAry']['flag'];

        // viewクラスの呼び出し
        $vi = new View();

        // $viに値を入れていく
        $vi->setAssign("title", "ippin管理画面 | レシピ編集画面");
        $vi->setAssign("cssPath", "css/admin.css");
        $vi->setAssign("bodyId", "recipeEdit");
        $vi->setAssign("h1Title", "レシピ編集画面");
        $vi->setAssign("main", "recipeEdit");
        $vi->setAssign("editedRecipe", $editedRecipe);
        $vi->setAssign("allFoodsList", $allFoodsList);
        $vi->setAssign("recipeIds", $recipeIds);
        $vi->setAssign("recipeInfo", $recipeInfo);
        $vi->setAssign("foodIds", $foodIds);
        $vi->setAssign("flag", $flag);

        // $viの値を$_SESSIONに渡して使えるようにする
        $_SESSION['viewAry'] = $vi->getAssign();

        // templateUserに$viを渡す
        $vi ->screenView("templateAdmin");
    
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
// echo '$editedRecipeの配列';
// print_r($editedRecipe);
// echo '<br>';
// print_r($results);
// echo '<br>';
// print_r($editResult);
// echo '<br>';

// echo '</pre>';
