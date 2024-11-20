<?php
require_once 'DbManager.php';
require_once 'ResultController.php';

// データ件数を取得するためのクラスです
class CountSql {
    private string $msgTitle; //処理結果のタイトル
    private string $msgTxt; //処理結果のメッセージ
    private int $linkId; //処理結果の戻るボタン用リンクID
    private string $fValuesStr; //食材IDテキスト
    private int $flag; //有効(1)・無効(0)・全件(9)
    private PDO $db;

    public function __construct(string $msgTitle, int $linkId)
    {     
        $this->msgTitle = $msgTitle;
        $this->msgTxt = '';
        $this->linkId = $linkId;
        $this->fValuesStr = '';
        $this->flag = 9;
        
        $dbh = new DbManager();
        $this->db = $dbh->getDb();
    }

    // 期待される戻り値 : 数字
    // 全件数取得(##, 9) , 有効(##, 1), 無効(##, 0)
    public function getCount(string $fValuesStr, int $flag) {
        $this->fValuesStr = $fValuesStr;
        $this->flag = $flag;

        $sql = $this->createSql();
        $stt = $this->db->prepare($sql);
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchColumn();
        } else {
            $this->msgTxt = '件数が取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    private function createSql() {
        // 全レシピの総数をカウント
        $sqlTxt = "SELECT COUNT(*) as recipe_count FROM recipe";
        if ($this->fValuesStr == '##') {
            if ($this->flag != 9) {
                // 全レシピの有効or無効の数をカウント
                $sqlTxt .= " WHERE recipeFlag = $this->flag";
            }
        } else {
            // 特定の食材レシピの総数をカウント
            $sqlTxt .= " WHERE foodValues = '$this->fValuesStr'";
            if ($this->flag != 9) {
                // 特定の食材レシピの有効or無効の数をカウント
                $sqlTxt .= " AND recipeFlag = $this->flag";
            }
        }
        return $sqlTxt;
    }

}