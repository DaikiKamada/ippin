<pre>
<?php
// require_once "../common/InsertSql.php";
// require_once '../common/SelectSql.php';
require_once '../common/UpdateSql.php';

// food版：POSTした内容を連想配列に代入
$_POST = ['foodName' => '魚', 'foodCatId' => 2];
$foodInfo = $_POST;


# insertFoodMテスト用
// $obj = new InsertSql('食材追加', 0);
// $result = $obj->insertFoodM($foodInfo);


// 日時を取得して連想配列に追加
$foodIds = [5, 4];
$_POST = ['recipeName' => 'カプレーゼ8', 'foodIds' => $foodIds, 'url' => 'url', 'howtoId' => 1, 'comment' => 'コメント', 'recipeFlag' => 0,  'memo' => 'メモ', 'img' => 'img', 'userId' => 1, 'siteName' => 'サイトネーム'];
// print_r ($_POST);
$recipeInfo = $_POST;

$lastUpdate = getDatestr();
$recipeInfo['lastUpdate'] = $lastUpdate;

// foodIdをソートして連想配列に追加
$foodValues = sortFoodIds($recipeInfo['foodIds']);
// print $foodValues;
$recipeInfo['foodValues'] = $foodValues;
print_r($recipeInfo);

// recipe版：POSTした内容を連想配列に代入
// function foodIdsinArr(array $recipeInfo):array {
//     $foodIds = [];
//     if (is_int($recipeInfo['foodId1'])) {
//         $foodIds[] = $recipeInfo['foodId1'];
//     }
//     if (is_int($recipeInfo['foodId2'])) {
//         $foodIds[] = $recipeInfo['foodId2'];
//     }
//     if (is_int($recipeInfo['foodId3'])) {
//         $foodIds[] = $recipeInfo['foodId3'];
//     }
//     return $foodIds;
// }

// テスト用
// $obj = new InsertSql('insert処理', 0);
// $result = $obj->insertRecipeT($recipeInfo);
// print_r ($result->getResult());

// テスト用(select)
// $arr = [1, 2, 3];
// $arr = [2, 5, 3];
// $obj = new SelectSql('食材を取得', 0);
// $result = $obj->getSelectedFood($arr);
// print_r($result);

// テスト用(update)
$arr = ['recipeId'=>20, 'recipeName'=>'カプレーゼ6', 'foodValues'=>'#4#5#', 'url'=>'test', 'howtoId'=>1, 'comment'=>'neko', 'recipeFlag'=>1, 'memo'=>'neko', 'userId'=>1, 'lastUpdate'=>'2024', 'siteName'=>'rakuten', 'img'=>'img'];
$arr2[] = $arr;
$obj = new UpdateSql('テーブルを更新', 0);
$result = $obj->updateRecipeT($arr2);