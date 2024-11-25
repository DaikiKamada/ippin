<?php
require_once 'DbManager.php';
require_once 'ResultController.php';
require_once 'CountSql.php';
require_once 'Utilities.php';

class UrlCheckSql {
    private string $msgTitle; // 処理結果のタイトル
    private string $msgTxt; // 処理結果のメッセージ
    private int $linkId;
    private string $fValuesStr; // 食材IDテキスト
    private int $flag; // 有効(1)・無効(0)・全件(9)
    private PDO $db;

    // コンストラクタ
    public function __construct(string $msgTitle, int $linkId, string $fValuesStr, int $flag) {     
        $this->msgTitle = $msgTitle;
        $this->msgTxt = '';
        $this->linkId = $linkId;
        $this->fValuesStr = $fValuesStr;
        $this->flag = 9;

        $dbh = new DbManager();
        $this->db = $dbh->getDb();
    }

    // URLの有効性を確認するメソッド
    public function checkRecipeUrls() {
        try {
            $sql = "SELECT recipeid, recipeName, url FROM recipe";
            $sql = "SELECT * FROM recipe";
            $stt = $this->db->prepare($sql);
            if ($stt->execute()) {  // execute出来たら
                $obj = new CountSql('URLチェック用のレシピ総件数カウント', 0);
                $total = $obj->getCount('##', 9);
                if (checkClass($total)) {
                    return $total;
                }
                else {
                    while ($row = $stt->fetch(PDO::FETCH_ASSOC)) {
                        $url = $row['url'];
                        if (!empty($url) && !$this->isUrlValid($url)) {
                            $brokenLinks[] = $row;
                        }
                        // 進捗バーの処理
                        if (empty($count)){
                            $count = 1;
                        }
                        progressBar($total, $count);
                        $count += 1;
                    }
                }
            }
            else {
                $this->msgTxt = 'executeができませんでした';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }

            // returnその1.リンク切れレシピの情報を持った連想配列のみを返す
            // print_r ($brokenLinks);
            return $brokenLinks;

            // returnその2.リンク切れレシピの情報を持った要素(連想配列)と、リンク切れレシピの件数を格納した要素(値)を格納した配列を返す
            // $brokenCount = count($brokenLinks);
            // return $result = ['リンク切れレシピ情報'=>$brokenLinks, 'リンク切れレシピ数'=>$brokenCount];

        } catch (PDOException $e) {
            echo "DB操作エラー: " . $e->getMessage();
        }
    }

    // URLの有効性を確認する関数
    private function isUrlValid($url) {
        if (empty($url)) {
            return false;
        }
        $headers = @get_headers($url);
        return $headers && strpos($headers[0], '200') !== false;
    }

    // URLカラムが空またはNULLのレコード件数を取得するメソッド
    public function getEmptyUrlCount(): int {
        try {
            // 空またはNULLの件数を取得するクエリ
            $sql = "SELECT COUNT(*) as empty_url_count FROM recipe WHERE url IS NULL OR url = ''";
            $stt = $this->db->prepare($sql);
            $stt->execute();
            
            // 結果を取得
            $result = $stt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['empty_url_count']; // 結果を返す
        } catch (PDOException $e) {
            echo "DB操作エラー: " . $e->getMessage();
            return 0; // エラー時は0を返す
        }
    }
    // // レコードの件数を取得するメソッド
    // public function getCount() {
    //     try {
    //         $sql = $this->createCountSql();
    //         $stt = $this->db->prepare($sql);
    //         $stt->execute();
    //         $result = $stt->fetch(PDO::FETCH_ASSOC);
    //         return $result;
    //     } catch (Exception $e) {
    //         $this->msgTxt = '件数が取得できません';
    //         return new ResultController(0, $this->msgTitle, $this->msgTxt);
    //     }
    // }

    // クエリを生成するプライベートメソッド
    private function createCountSql() {
        $sqlTxt = "SELECT COUNT(*) as recipe_count FROM recipe";
        if ($this->fValuesStr == '##') {
            if ($this->flag != 9) {
                $sqlTxt .= " WHERE recipeFlag = $this->flag";
            }
        } else {
            $sqlTxt .= " WHERE foodValues = '$this->fValuesStr'";
            if ($this->flag != 9) {
                $sqlTxt .= " AND recipeFlag = $this->flag";
            }
        }
        return $sqlTxt;
    }
}


// // 使用例
// $countSql = new UrlCheckSql ('##', 1, 'リンクチェック');
// $countSql->checkRecipeUrls(); // URLチェック
// $result = $countSql->getCount(); // 件数取得
// if ($result) {
//     echo "該当レシピ件数: " . $result['recipe_count'] . "件<br>";
// }


// 進捗バー
function progressBar(int $total, int $count) {
    if ($count == 1) {
        echo <<<HTML
            <style>
                p progress { width: 500px; height: 30px; }
            </style>
            <p>
            HTML;
    }
    $num = $count - 1;
    echo '<progress value="'.$count.'" max="'.$total.'">'.floor(($count / $total * 100)).'%</progress>';
    echo 
    <<<HTML
        <style>
        p progress[value="{$num}"] {display:none}
        </style>
    HTML;
    if ($total == $count) { 
        echo
        <<<HTML
            <style>
                p progress[value="{$count}"] {display:none}
            </style>
        HTML;
    }
    ob_flush();
    flush();
    // #テスト用(わざと処理を遅延させる)
    // usleep(100000);
    // usleep(200000);
}

// $obj = new UrlCheckSql('URLが有効かチェック', 9, '##', 9);
// $result = $obj->checkRecipeUrls();
// print_r ($result);