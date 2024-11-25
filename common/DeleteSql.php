<?php
require_once 'DbManager.php';
require_once 'ResultController.php';
class DeleteSql
{
    private string $msgTitle; //処理結果のタイトル
    private string $msgTxt; //処理結果のメッセージ

    private int $linkId; //処理結果の戻るボタン用リンクID


    private PDO $db;

    public function __construct(string $msgTitle, int $linkId)
    {
        $this->msgTitle = $msgTitle;
        $this->msgTxt = '';
        $this->linkId = $linkId;

        $dbh = new DbManager();
        $this->db = $dbh->getDb();
    }

    // foodMasterから食材を削除

    public function deleteFoodM(int $foodId, string $foodName): ResultController
    {

        // 更新対象のfoodIdが存在するかを確認
        $checkId = $this->checkRecord('foodm', 'food', $foodId, 'none');
        // レコードの存在チェックに失敗した場合、ResultCtl(エラー)を返す
        if (checkClass($checkId)) {
            return $checkId;
        }
        // レコード存在チェックが成功した場合は削除処理
        if ($checkId) {
            $stt = $this->db->prepare("DELETE FROM foodm WHERE foodId = :foodId;");
            $stt->bindvalue(':foodId', $foodId);

            // SQL実行結果をチェック
            $this->db->beginTransaction();
            if ($stt->execute()) {
                if ($stt->rowCount()) {
                    $this->db->commit();
                    $this->msgTxt = "{$foodName}：食材の削除に成功しました";
                    return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
                } else {
                    $this->db->rollBack();
                    $this->msgTxt = "{$foodName}：食材の削除に失敗しました";
                    return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
                }
            } else {
                // レコード存在チェックが失敗した場合
                $this->msgTxt = "{$foodName}：食材の削除処理中にエラーが発生しました";
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }

        } else {
            // レコード存在チェックが失敗した場合
            $this->msgTxt = "{$foodName}：更新対象の食材IDが存在しません";
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // DBにレコードが存在するかチェック
    private function checkRecord(string $tableName, string $colName, int $myId, string $myValue): bool|ResultController
    {
        $sql = "SELECT * FROM $tableName WHERE $colName";
        if ($myValue == 'none') {
            $sql .= "Id = $myId";
        } else {
            $sql .= "Name = '$myValue' AND $colName" . "Id <> $myId";

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
            $this->msgTxt = "{$myValue}：レコードチェックに失敗しました";
            return new ResultController(0, 'レコードチェック', $this->msgTxt, $this->linkId);
        }
    }


    // recipeTableからレシピを削除
    public function deleteRecipeT(array $arrayR)
    {
        $darray = [];
        foreach ($arrayR as $a) {
            // 選択項目の配列を個別のIDに展開を使用して、配列をそのまま引数として渡す
            $result = $this->deleteRecord($a);
            $darray[$a] = $result;
            // var_dump($a);
            // var_dump($result);
        }
        return $darray;
    }

    function deleteRecord(int $recipeId): ResultController
    {

        // recipeIdが存在するかを確認
        $checkId = $this->checkRecord('recipe', 'recipe', $recipeId, 'none');
        // レコードの存在チェックに失敗した場合、ResultCtl(エラー)を返す
        if (checkClass($checkId)) {
            return $checkId;
        }

        if ($checkId) {

            $this->db->beginTransaction();
            $stt = $this->db->prepare("DELETE FROM recipe WHERE recipeId = :recipeId;");
            $stt->bindvalue(':recipeId', $recipeId);
            if ($stt->execute()) {
                if ($stt->rowCount()) {
                    $this->db->commit();
                    $this->msgTxt = 'レシピの削除に成功しました';
                    return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
                } else {
                    $this->db->rollBack();
                    $this->msgTxt = 'レシピの削除に失敗しました';
                    return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
                }
            } else {
                $this->db->rollBack();
                $this->msgTxt = 'レシピの削除処理中にエラーが発生しました';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }
        } else {
            // レコード存在チェックが失敗した場合
            $this->msgTxt = '更新対象のレシピIDが存在しません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }




        // public function setMsgTitle(string $msgTitle){
        //     $this->msgTitle = $msgTitle;
        // }

        // public function getMsgTitle(): string {
        //     return $this->msgTitle;
        // }
    }
}
