<?php
require_once 'DbManager.php';
require_once 'ResultController.php';
require_once 'view/View.php';

// ログイン認証のためのクラスです
class UserLogin {
    private string $msgTitle; //処理結果のタイトル
    private string $msgTxt; //処理結果のメッセージ
    private int $linkId; //処理結果の戻るボタン用リンクID
    private string $userMail; //ログイン用メールアドレス
    private string $userPw; //ログインPW
    private int $userFlag; //権限フラグ 管理者(0)・ユーザ(1)
    private PDO $db;

    public function __construct(string $msgTitle, int $linkId)
    {     
        $this->msgTitle = $msgTitle;
        $this->msgTxt = '';
        $this->linkId = $linkId;
        $this->userMail = '';
        $this->userPw = '';
        $this->userFlag = 1;
        
        $dbh = new DbManager();
        $this->db = $dbh->getDb();
    }

    // 認証情報をDBから取得し、結果を返す（配列バージョン）
    // 戻り値　処理成功：配列　｜　エラー：ResultController
    public function getUserInfo(string $userMail, string $userPw, int $userFlag): array|ResultController {
        $this->userMail = $userMail;
        $this->userPw = $userPw;
        $this->userFlag = $userFlag;
        // ユーザー情報の配列 or エラー情報（ResultController）を返す
        return $this->selectUserList();
    }

    // 認証情報をDBから取得し、結果を返す（Bool値バージョン）
    public function checkUserInfo(string $userMail, string $userPw, int $userFlag): bool {
        $this->userMail = $userMail;
        $this->userPw = $userPw;
        $this->userFlag = $userFlag;

        $result = $this->selectUserList();
        // ユーザー情報が正しければ True or エラー情報（ResultController）を返す
        if (checkClass($result)) {
            return false;
        } else {
            return true;
        }

    }

    // 認証情報をDBから取得し、結果を返すメソッド
    private function selectUserList(): array|ResultController {
        //認証情報を取得
        $stt = $this->db->prepare("SELECT userId, nickName, password, userFlag FROM user WHERE mailAddress = '{$this->userMail}';");
        // SQL実行結果をチェック
        if ($stt->execute()) {
            // 認証情報が存在するかチェック
            if ($row = $stt->fetch(PDO::FETCH_ASSOC)) {
                // PWが一致するかチェック
                if ($row['password'] == $this->userPw) {
                    // ユーザ権限をチェック
                    if ($row['userFlag'] == $this->userFlag) {
                        return $row;
                    } else {
                        $this->msgTxt = '管理者権限がありません';
                        return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
                    }
                } else {
                    $this->msgTxt = 'PWが正しくありません';
                    return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
                }
            } else {
                $this->msgTxt = 'ID（メールアドレス）が正しくありません';
                    return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }
        } else {
            $this->msgTxt = 'ユーザ認証情報が取得できませんでした';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }

    // 認証エラーのデフォルト画面を返す
    public function getLoginErrView():View {
        $vi = new View();
        $vi->setAssign("title", "ippin管理画面 | ログイン認証エラー");
        $vi->setAssign("cssPath", "css/user.css");
        $vi->setAssign("bodyId", "error");
        $vi->setAssign("main", "error");
        $vi->setAssign("h1Title", "認証情報エラー");
        $vi->setAssign("resultMsg", "認証情報が正しくありません。再度ログインしてください。");
        $vi->setAssign("linkUrl", "login.php");
        $vi->setAssign("resultNo", "0");

        return $vi;
    }


    // PWの変更を実施
    // 戻り値　ResultController
    public function changePassword(string $userMail, string $newUserPw,): ResultController {
        $stt = $this->db->prepare("UPDATE user SET password = :password WHERE mailAddress = :userMail;");
        $stt->bindValue(':password', $newUserPw);
        $stt->bindValue(':userMail', $userMail);

        // SQL実行結果をチェック
        if ($stt->execute()) {
            if ($stt->rowCount()) {
                $this->msgTxt = 'PWの変更に成功しました';
                return new ResultController(1, $this->msgTitle, $this->msgTxt, $this->linkId);
            } else {
                $this->msgTxt = 'PWの変更に失敗しました（メールアドレスかPWが正しくありません）';
                return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
            }
        } else {
            $this->msgTxt = 'PWの変更処理を実行できませんでした';
            return new ResultController(0, $this->msgTitle, $this->msgTxt, $this->linkId);
        }
    }




}