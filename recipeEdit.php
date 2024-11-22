<?php
session_start();

require_once 'common/UpdateSql.php';
require_once 'common/SelectSql.php';
require_once 'common/Utilities.php';
require_once 'view/View.php';

// SESSIONにeditedRecipeキーが存在すればそれをコピー、なければPOSTの値をコピー ※ページをリロードしても大丈夫なように
if(array_key_exists('editedRecipe', $_SESSION['viewAry'])) {
    $recipeIds = $_SESSION['viewAry']['recipeIds'];
    $recipeInfo = $_SESSION['viewAry']['recipeInfo'];
}
else {
    $recipeIds = $_POST['choicedRecipe'];
    $recipeInfo = $_SESSION['viewAry']['recipeList'];
}

// 全ての食材を取得
$selectFoods = new SelectSql('全ての食材を取得', 0);
$allFoodsList = $selectFoods->getFood();

// 配列を用意(編集したいレシピを入れる)
$editedRecipe = [];

// 編集したいレシピの一覧を取得
for($i = 0; $i < count($recipeInfo); $i++) {
    for($x = 0; $x < count($recipeIds); $x++) {
        if($recipeInfo[$i]['recipeId'] == $recipeIds[$x]) {
            $editedRecipe[] = $recipeInfo[$i];
        }
    }
}

// 編集ボタンが押されたら、recipteTableを更新してrecipeManagementに戻る
if(array_key_exists('update', $_POST)) {
    if($_POST['update'] == 'update') {

        // 配列を用意
        $editedInfo = [];

        // POSTの内容をコピー
        $copyPost = $_POST;

        // $copyPostの、キーが数字の部分だけをコピー
        for($i = 0; $i < count($editedRecipe); $i++) {
            $editedInfo[$i] = $copyPost[$i];
        }

        // print_r ($editedInfo);

        // UpdateSqlのインスタンスを作成
        $updateRecipe = new UpdateSql('レシピを更新', 0);
    
        // 複数のレコードを更新する
        $result = $updateRecipe->updateRecipeT($editedInfo);
    
        // 処理結果に応じての処理ができたらいいな～
        
        // updateが終わったら、recipeManagementへリダイレクト
        // header('Location: recipeManagement.php');
    }
    elseif($_POST['update'] == 'cancel') {
        // 処理をせずにrecipeManagementへリダイレクト
        header('Location: recipeManagement.php');
    }
}

// recipeManagement.phpで選択した食材、レシピフラグをSESSIONに渡すために、変数にコピー
$foodIds = $_SESSION['viewAry']['foodIds'];
$flag = $_SESSION['viewAry']['flag'];

$vi = new View();

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

$_SESSION['viewAry'] = $vi->getAssign();

$vi ->screenView("templateAdmin");

// デバッグ用※あとで消そうね！
echo '<pre>';
// echo '$_POSTの配列';
// print_r($_POST);
// echo '<br>';
// echo '$_SESSIONの配列';
// print_r($_SESSION['viewAry']['recipeList']);
// echo '$_SESSIONの配列';
// print_r($_SESSION);
// echo '$resultの配列';
// print_r($result);
// echo '$foodIdsの配列';
// print_r($foodIds);
// echo '$recipeInfoの配列';
// print_r($recipeInfo);
// echo '$_POSTの配列';
// print_r($_POST);
// echo '$foodsListの配列';
// print_r($foodsList);
// echo '$recipeListの配列';
// print_r($recipeList);
// echo '$editedInfoの配列';
// print_r($editedInfo)
print_r ($editedInfo);;
echo '</pre>';