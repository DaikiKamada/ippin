<?php
require_once 'DbManager.php';
require_once 'ResultController.php';
require_once 'Utilities.php';

// データを追加するためのクラスです
class InsertSql{
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

    // foodMasterに食材を追加
    function insertFoodM (string $foodName, int $foodCatId): ResultController {

        //同じ名前の材料がないかチェック
        $checkResult = $this->checkRecord('foodm', 'foodName', $foodName);
        // 重複チェックに失敗した場合、ResultCtl(エラー)を返す
        if (gettype($checkResult) == 'object' && get_class($checkResult) == 'ResultController') { 
            return $checkResult;
        }
        // 重複チェックの結果、 同じ名前の材料がない場合
        if ($checkResult) {
            $stt = $this->db->prepare("INSERT INTO foodm (foodName, foodCatId) VALUES (:foodName, :foodCatId);");
            $stt->bindvalue(':foodName', $foodName);
            $stt->bindvalue(':foodCatId', $foodCatId);

            // SQL実行結果をチェック
            $this->db->beginTransaction();
            if ($stt->execute()) {
                $this->db->commit();
                $this->msgTxt = '食材の追加に成功しました';
                return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
            } else {
                $this->db->rollBack();
                $this->msgTxt = '食材の追加に失敗しました';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }
        }
        else { //同じ名前の材料がある場合
            $this->msgTxt = '同じ材料名のデータが既に登録されています';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }

    }

    // recipeTableにレシピを追加
    function insertRecipeT($recipeName, $foodIds, $url, $howtoId, $comment, $memo, $img, $userId, $siteName): ResultController {
      
        //同じ名前のレシピがないかチェック
        $checkResult = $this->checkRecord('recipe', 'recipeName', $recipeName);
        // 重複チェックに失敗した場合、ResultCtl(エラー)を返す
        if (gettype($checkResult) == 'object' && get_class($checkResult) == 'ResultController') { 
            return $checkResult;
        }
        // 重複チェックの結果、 同じ名前のレシピがない場合
        if ($checkResult) { 
            $this->db->beginTransaction();
            $lastUpdate = getDateStr(); //日時取得
            $foodValues = sortFoodIds($foodIds); //IDのソート
            $stt = $this->db->prepare("INSERT INTO recipe (recipeName, foodValues, url, howtoId, comment, memo, img, userId, lastUpdate, siteName)
                                            VALUES (:recipeName, :foodValues, :url, :howtoId, :comment, :memo, :img, :userId, :lastUpdate, :siteName);");
            $stt->bindvalue(':recipeName', $recipeName);
            $stt->bindvalue(':foodValues', $foodValues);
            $stt->bindvalue(':url', $url);
            $stt->bindvalue(':howtoId', $howtoId);
            $stt->bindvalue(':comment', $comment);
            $stt->bindvalue(':memo', $memo);
            $stt->bindvalue(':img', $img);
            $stt->bindvalue(':userId', $userId);
            $stt->bindvalue(':lastUpdate', $lastUpdate);
            $stt->bindvalue(':siteName', $siteName);

            // SQL実行結果をチェック
            if ($stt->execute()) {
                $this->db->commit();
                $this->msgTxt = 'レシピの追加に成功しました';
                return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
            } else {
                $this->db->rollBack();
                $this->msgTxt = 'レシピの追加に失敗しました';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }
        }
        else { //同じ名前のレシピがある場合
            $this->msgTxt = '同じレシピ名のデータが既に登録されています';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }

    }

    // DBに同じレコードが既にあるかチェック
    private function checkRecord(string $tableName, string $columnName, string $valueName) {
        $stt = $this->db->prepare("SELECT $columnName FROM $tableName WHERE $columnName = '$valueName';");
        if ($stt->execute()) {
        // 同じレコードが存在するかチェック
            if ($stt->rowCount() == 0) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->msgTxt = 'レコードチェックに失敗しました';
            return new ResultController(0, '重複レコードチェック', $this->msgTxt, $this->linkId);
        }
    }

}
