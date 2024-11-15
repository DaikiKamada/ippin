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
    function insertFoodM (array $foodInfo): ResultController {

        //同じ名前の材料がないかチェック
        $checkResult = $this->checkRecord('foodm', 'foodName', $foodInfo['foodName']);
        // 重複チェックに失敗した場合、ResultCtl(エラー)を返す
        if (checkClass($checkResult)) { 
            return $checkResult;
        }
        // 重複チェックの結果、 同じ名前の材料がない場合
        if ($checkResult) {
            $stt = $this->db->prepare("INSERT INTO foodm (foodName, foodCatId) VALUES (:foodName, :foodCatId);");
            $stt->bindvalue(':foodName', $foodInfo['foodName']);
            $stt->bindvalue(':foodCatId', $foodInfo['foodCatId']);

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
    function insertRecipeT(array $recipeInfo): ResultController {
      
        //同じ名前のレシピがないかチェック
        $checkResult = $this->checkRecord('recipe', 'recipeName', $recipeInfo['recipeName']);
        // 重複チェックに失敗した場合、ResultCtl(エラー)を返す
        if (checkClass($checkResult)) { 
            return $checkResult;
        }
        // 重複チェックの結果、 同じ名前のレシピがない場合
        if ($checkResult) { 
            $this->db->beginTransaction();
            $stt = $this->db->prepare("INSERT INTO recipe (recipeName, foodValues, url, howtoId, comment, memo, img, userId, lastUpdate, siteName)
                                            VALUES (:recipeName, :foodValues, :url, :howtoId, :comment, :memo, :img, :userId, :lastUpdate, :siteName);");
            $stt->bindvalue(':recipeName', $recipeInfo['recipeName']);
            $stt->bindvalue(':foodValues', $recipeInfo['foodValues']);
            $stt->bindvalue(':url', $recipeInfo['url']);
            $stt->bindvalue(':howtoId', $recipeInfo['howtoId']);
            $stt->bindvalue(':comment', $recipeInfo['comment']);
            $stt->bindvalue(':memo', $recipeInfo['memo']);
            $stt->bindvalue(':img', $recipeInfo['img']);
            $stt->bindvalue(':userId', $recipeInfo['userId']);
            $stt->bindvalue(':lastUpdate', $recipeInfo['lastUpdate']);
            $stt->bindvalue(':siteName', $recipeInfo['siteName']);

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
    private function checkRecord(string $tableName, string $columnName, string $valueName): bool|ResultController {
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
