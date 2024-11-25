<?php
require_once 'DbManager.php';
require_once 'ResultController.php';
require_once 'Utilities.php';

class UpdateSql
{
    private string $msgTitle; //処理結果のタイトル
    private string $msgTxt; //処理結果のメッセージ
    private int $linkId; //処理結果の戻るボタン用リンクID

    private PDO $db;


    public function __construct(string $msgTitle, int $linkId) {
        $this->msgTitle = $msgTitle;
        $this->msgTxt = '';
        $this->linkId = $linkId;

        $dbh = new DbManager();
        $this->db = $dbh->getDb();
    }

    // foodMasterの食材を更新
    public function updateFoodM(int $foodId, string $foodName, int $foodCatId): ResultController {

        // 更新対象のfoodIdが存在するかを確認
        $checkId = $this->checkRecord('foodm', 'food', $foodId, 'none');
        // レコードの存在チェックに失敗した場合、ResultCtl(エラー)を返す
        if (checkClass($checkId)) { 
            return $checkId;
        }

        // レコード存在チェックが成功した場合
        if ($checkId) {
            // 食材名の重複チェックを実施
            $checkName = $this->checkRecord('foodm', 'food', $foodId, $foodName);
            // 食材名の重複チェックに失敗した場合、ResultCtl(エラー)を返す
            if (checkClass($checkName)) { 
                return $checkName;
            }
            // 重複したレコードがある場合、ResultCtl(エラー)を返す
            if ($checkName) { 
                $this->msgTxt = '同じ名前の食材がすでに存在します';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            
            } else { // 重複がなければアップデート処理を実行
                $stt = $this->db->prepare("UPDATE foodm SET foodName = :foodName, foodCatId = :foodCatId WHERE foodId = :foodId");
                $stt->bindValue(':foodName', $foodName);
                $stt->bindValue(':foodCatId', $foodCatId);
                $stt->bindValue(':foodId', $foodId);

                // SQL実行結果をチェック
                $this->db->beginTransaction();
                if ($stt->execute()) {
                    $this->db->commit();
                    $this->msgTxt = '食材の更新に成功しました';
                    return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
                } else {
                    $this->db->rollBack();
                    $this->msgTxt = '食材の更新に失敗しました';
                    return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
                }
            }
        } else {
            // レコード存在チェックが失敗した場合
            $this->msgTxt = '更新対象の食材IDが存在しません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }

    }

    // DBにレコードが存在するかチェック
    private function checkRecord(string $tableName, string $colName, int $myId, string $myValue): bool|ResultController {
        $sql = "SELECT * FROM $tableName WHERE $colName";
            if ($myValue == 'none') {
                $sql .= "Id = $myId";
            } else {
                $sql .= "Name = '$myValue' AND $colName"."Id <> $myId";

            }
        $sql .= ";";
        $stt = $this->db->prepare($sql);
        if ($stt->execute()) {
        // レコードが存在するかチェック
            if ($stt->rowCount() == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            $this->msgTxt = 'レコードチェックに失敗しました';
            return new ResultController(0, 'レコードチェック', $this->msgTxt, $this->linkId);
        }
    }

    // 複数のrecipeTableのレシピを更新する
    // 更新結果（ResultController）をIDをKeyに連想配列にして返す
    public function updateRecipeT(array $recipeArr): array {
        // かまちゃんへ　この名前の連想配列でお願いします
        // $recipeId = $recipeArr['recipeId'];
        // $recipeName = $recipeArr['recipeName'];
        // $foodValues = $recipeArr['foodValues'];
        // $url = $recipeArr['url'];
        // $howtoId = $recipeArr['howtoId'];
        // $comment = $recipeArr['comment'];
        // $memo = $recipeArr['memo'];
        // $img = $recipeArr['img'];
        // $userId = $recipeArr['userId'];
        // $lastUpdate = $recipeArr['lastUpdate'];
        // $siteName = $recipeArr['siteName'];
        $resultArr = [];
        foreach ($recipeArr as $arr) {
            // print_r($arr);
            $result =$this->updateRecord($arr);
            $resultArr[$arr['recipeId']] = $result;
        }
        return $resultArr;
    }

    // recipeTableのレシピを更新する
    private function updateRecord(array $recipeArr): bool|ResultController {
        $checkId = $this->checkRecord('recipe', 'recipe', $recipeArr['recipeId'], 'none');
        // レコードの存在チェックに失敗した場合、ResultCtl(エラー)を返す
        if (checkClass($checkId)) { 
            return $checkId;
        }
         // レコード存在チェックが成功した場合
         if ($checkId) {
            // レシピ名の重複チェックを実施
            $checkName = $this->checkRecord('recipe', 'recipe', $recipeArr['recipeId'], $recipeArr['recipeName']);
            // レシピ名の重複チェックに失敗した場合、ResultCtl(エラー)を返す
            if (checkClass($checkName)) { 
                return $checkName;
            }
            // 重複したレコードがある場合、ResultCtl(エラー)を返す
            if ($checkName) { 
                $this->msgTxt = '同じ名前のレシピがすでに存在します';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            
            } else { // 重複がなければアップデート処理を実行
                
                $stt = $this->db->prepare(
                    "UPDATE recipe SET recipeName = :recipeName, foodValues = :foodValues, url = :url, howtoId = :howtoId, 
                    comment = :comment, recipeFlag = :recipeFlag, memo = :memo, img = :img, userId = :userId, lastUpdate = :lastUpdate, siteName = :siteName
                    WHERE recipeId = :recipeId"
                );
                $stt->bindValue(':recipeName', $recipeArr['recipeName']);
                $stt->bindValue(':foodValues', $recipeArr['foodValues']);
                $stt->bindValue(':url', $recipeArr['url']);
                $stt->bindValue(':howtoId', $recipeArr['howtoId']);
                $stt->bindValue(':comment', $recipeArr['comment']);
                $stt->bindValue(':recipeFlag', $recipeArr['recipeFlag']);
                $stt->bindValue(':memo', $recipeArr['memo']);
                $stt->bindValue(':img', $recipeArr['img']);
                $stt->bindValue(':userId', $recipeArr['userId']);
                $stt->bindValue(':lastUpdate', $recipeArr['lastUpdate']);
                $stt->bindValue(':siteName', $recipeArr['siteName']);
                $stt->bindValue(':recipeId', $recipeArr['recipeId']);

                // SQL実行結果をチェック
                $this->db->beginTransaction();
                if ($stt->execute()) {
                    $this->db->commit();
                    $this->msgTxt = 'レシピ情報の更新に成功しました';
                    return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
                } else {
                    $this->db->rollBack();
                    $this->msgTxt = 'レシピ情報の更新に失敗しました';
                    return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
                }
            }
        } else {
            // レコード存在チェックが失敗した場合
            $this->msgTxt = '更新対象のレシピIDが存在しません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

}
