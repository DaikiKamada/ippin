<pre>
    <?php
require_once "../common/insertSql.php";

// food版：POSTした内容を連想配列に代入
$_POST = ['foodName' => 'セロリ', 'foodCatId' => 1];
$foodInfo = $_POST;


# insertFoodMテスト用
$obj = new InsertSql('食材追加', 0);
$result = $obj->insertFoodM($foodInfo);


// 日時を取得して連想配列に追加
$_POST = ['recipeName' => 'カプレーゼ1', 'foodId1' => 2, 'foodId2' => 3, 'foodId3' => 1, 'url' => 'url', 'howtoId' => 1, 'comment' => 'コメント',  'memo' => 'メモ', 'img' => 'img', 'userId' => 1, 'siteName' => 'サイトネーム'];
print_r ($_POST);
$recipeInfo = $_POST;

$lastUpdate = getDatestr();
$recipeInfo['lastUpdate'] = $lastUpdate;

// foodIdをソートして連想配列に追加
$foodIds = foodIdsinArr($recipeInfo);
// print_r ($foodIds);
$foodValues = sortFoodIds($foodIds);
// print $foodValues;
$recipeInfo['foodValues'] = $foodValues;
print_r($recipeInfo);

// recipe版：POSTした内容を連想配列に代入
function foodIdsinArr(array $recipeInfo):array {
    $foodIds = [];
    if (is_int($recipeInfo['foodId1'])) {
        $foodIds[] = $recipeInfo['foodId1'];
    }
    if (is_int($recipeInfo['foodId2'])) {
        $foodIds[] = $recipeInfo['foodId2'];
    }
    if (is_int($recipeInfo['foodId3'])) {
        $foodIds[] = $recipeInfo['foodId3'];
    }
    return $foodIds;
}

// テスト用
// $obj = new InsertSql('insert処理', 0);
// $result = $obj->insertRecipeT($recipeInfo);
// print_r ($result->getResult());