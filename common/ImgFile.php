<?php
require_once 'DbManager.php';
require_once 'ResultController.php';

// 画像ファイルの処理を実施するクラスです
class ImgFile {
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

    // ファイル名に使用する新しいID番号を取得
    // 戻り値　処理成功：int(ID番号)　｜　エラー：ResultController
    public function getNewRecipeId(): mixed {
        $sql = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'ippindb' AND TABLE_NAME = 'recipe';";
        $stt = $this->db->prepare($sql);
        // SQL実行結果をチェック
        if ($stt->execute()) {
            return $stt->fetchColumn();
        } else {
            $this->msgTxt = '画像保存用のレシピIDデータの取得に失敗しました';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // ファイルのアップロードチェックを実行
    // 戻り値　処理成功：string（ファイル名）　｜　エラー：ResultController
    public function checkUplodeFile(int $NewRecipeId): string|ResultController {
        // ファイルのアップロードチェックを実施
        if (!$_FILES['upFile']['error'] == UPLOAD_ERR_OK) { // エラー時
            $msg = [
                UPLOAD_ERR_INI_SIZE => 'サイズ制限を超えています。<br>',
                UPLOAD_ERR_FORM_SIZE => 'サイズ制限を超えています。<br>',
                UPLOAD_ERR_PARTIAL => 'ファイルのアップロードが中断されました。<br>',
                UPLOAD_ERR_NO_FILE => 'ファイルはアップロードがされませんでした。<br>',
                UPLOAD_ERR_NO_TMP_DIR => 'ファイルの一時保存先が見つかりません。<br>',
                UPLOAD_ERR_CANT_WRITE => 'ディスクの書き込みに失敗しました。<br>',
                UPLOAD_ERR_EXTENSION => '拡張モジュールによってアップロードが中断されました。<br>'
            ];
            $this->msgTxt = $msg[$_FILES['upFile']['error']];
         } else { // 正常時
            // ファイル名の長さチェック
            if (strlen($_FILES['upFile']['name']) > 8190) {
                $this->msgTxt = 'ファイル名が8190文字を超えています。<br>';
            }// 拡張子の確認（jpgとjpegのみ許可）
            else if (
                    !in_array(strtolower(pathinfo($_FILES['upFile']['name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg'])
                ) {
                $this->msgTxt = 'jpgまたはjpeg形式の画像ファイルのみアップロード可能です。<br>';
            } // MIMEタイプの確認
            else if (
                !in_array(finfo_file(
                    finfo_open(FILEINFO_MIME_TYPE),
                    $_FILES['upFile']['tmp_name']
                ), ['image/jpg', 'image/jpeg'])
                ) {
                    $this->msgTxt = 'jpgまたはjpeg形式の画像ファイルのみアップロード可能です。<br>';
            } else {   
                // ファイル名を固定して.jpgで保存
                    return "recipe{$NewRecipeId}.jpg";
            }
        }
        // エラー情報を返す
        return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
    }

    // ファイルのアップロード処理を実行
    // 戻り値　処理成功：true　｜　エラー：ResultController
    public function fileUplode(string $fileName): bool|ResultController {
        // アップロードするファイルパスを取得
        $src = $_FILES['upFile']['tmp_name'];
        // アップロード処理を実行
        if (!move_uploaded_file($src, to: 'images/' . $fileName)) {
            $this->msgTxt = '画像のアップロードに失敗しました。<br>
                レシピデータを確認し、再度画像をアップロードしてください。';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        } else {
            return true;
        }
    }

}