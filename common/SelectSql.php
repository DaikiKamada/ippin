<?php
require_once 'common/DbManager.php';
require_once 'common/ResultController.php';
require_once 'common/Utilities.php';

// データを取得するためのクラスです
class SelectSql {
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

    // 食材マスタを取得
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    public function getFood(): mixed {
        $sql = "SELECT `foodId`, `foodName`, `foodCatId`, (SELECT `catName` FROM `foodcatm` WHERE `foodcatm`.`foodCatId` = `foodm`.`foodCatId`) AS `catName` FROM `foodm` ORDER BY `foodId` DESC";
        $stt = $this->db->prepare($sql);
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->msgTxt = '食材マスタデータが取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // getFoodList：食材マスタ全件＋レシピ件数
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    public function getFoodList(): mixed {
        $sql = "SELECT foodId, foodName, catName, 
                (SELECT COUNT(*) FROM `recipe` WHERE `recipe`.`foodValues` LIKE CONCAT('%', foodm.foodId, '%')) AS recipe_count, 
                (SELECT COUNT(*) FROM `recipe` WHERE `recipe`.`recipeFlag` = '0' AND `recipe`.`foodValues` LIKE CONCAT('%', foodm.foodId, '%')) AS flag0_count, 
                (SELECT COUNT(*) FROM `recipe` WHERE `recipe`.`recipeFlag` = '1' AND `recipe`.`foodValues` LIKE CONCAT('%', foodm.foodId, '%')) AS flag1_count 
                FROM `foodm` 
                JOIN `foodcatm` ON `foodm`.`foodCatId` = `foodcatm`.`foodCatId` 
                GROUP BY `foodm`.`foodId`";
        $stt = $this->db->prepare($sql);
        
        // SQL実行結果をチェック
        if ($stt->execute()) {
            // 結果を配列で返す
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->msgTxt = '食材マスタデータまたはレシピ件数が取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // レシピの詳細を取得
    // 戻り値　処理成功：配列　※０件の場合は、ResultController
    //  　｜　エラー：ResultController
    public function getRecipe(string $fValuesStr, int $flag): mixed {
        $this->fValuesStr = $fValuesStr;
        $this->flag = $flag;
        $sql = $this->createRecipeSql();
        $stt = $this->db->prepare($sql);
        // 食材の指定がある場合
        if ($fValuesStr !== '##') {
            $stt->bindParam(':fValues', $fValuesStr, PDO::PARAM_STR);
        }
        // 有効・無効の条件がある場合
        if ($flag !== 9) {
            $stt->bindValue(':flag', $flag, PDO::PARAM_INT);
        }
        // SQL実行結果をチェック
        if ($stt->execute()) {
            $row =$stt->fetchAll(PDO::FETCH_ASSOC);
            // レシピ件数をチェック
            if($row) {
                // 結果を配列で返す
                return $row;
            } else {
                $this->msgTxt = '該当するレシピがありません（検索結果０件）';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }

        } else {
            $this->msgTxt = 'レシピデータが取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // レシピ取得SQLの生成
    private function createRecipeSql(): string {
        $sqlTxt = "SELECT * FROM `recipe` JOIN `howtocatm` ON `recipe`.`howtoId` = `howtocatm`.`howtoId`";
        if ($this->fValuesStr == '##') {
            if ($this->flag != 9) {
                $sqlTxt .= " WHERE recipeFlag = :flag";
            }
        } else {
            $sqlTxt .= " WHERE foodValues = :fValues";
            if ($this->flag != 9) {
                $sqlTxt .= " AND recipeFlag = :flag";
            }
        }
        return $sqlTxt;
    }

// 選択された食材の食材マスタを取得
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    public function getSelectedFood(array $fValues): mixed {
        $txt = '';
        foreach ($fValues as $id) {
            $txt .= $id.', ';
        }
        $txt = substr($txt, 0, strlen($txt) - 2);
        $sql = "SELECT foodId, foodName, catName 
            FROM `foodm` JOIN `foodcatm` 
            ON `foodm`.`foodCatId` = `foodcatm`.`foodCatId`";
            if ($txt == '') {
                $sql .= ";";
            } else {
                $sql .= "WHERE foodId IN ($txt);";
            }
        $stt = $this->db->prepare($sql);
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->msgTxt = '食材マスタデータが取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }
    
    // 食材カテゴリマスタを取得
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    function getfoodCatM(): mixed {
        $stt = $this->db->prepare('SELECT * FROM foodcatm');
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->msgTxt = '食材カテゴリのデータが取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // レシピカテゴリマスタを取得
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    function getHowToCatM(): mixed {
        $stt = $this->db->prepare('SELECT * FROM howtocatm');
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->msgTxt = 'レシピカテゴリのデータが取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // レシピIDを指定してレシピの情報を取得
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    public function getSelectedRecipe(array $rValues): mixed {
        $txt = '';
        foreach ($rValues as $id) {
            $txt .= $id.', ';
        }
        $txt = substr($txt, 0, strlen($txt) - 2);
        $sql = "SELECT * FROM `recipe` 
        JOIN `howtocatm` ON `recipe`.`howtoId` = `howtocatm`.`howtoId` 
        WHERE recipeId IN ($txt);";

        $stt = $this->db->prepare($sql);
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $this->msgTxt = 'レシピデータが取得できません';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }
}