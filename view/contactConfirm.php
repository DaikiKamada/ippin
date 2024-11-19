<main>
<h2 class="contact_confirm_title">お問い合わせ内容の確認</h2>
        <div class="contact_confirm_box container">
            <h3>お名前</h3>
            <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
            <?php $contactName = $this->assign['contactName']; ?>
            <p><?= $contactName ?></p>
            <hr>
            <h3>メールアドレス</h3>
            <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
            <?php $contactEmail = $this->assign['contactEmail']; ?>
            <p><?= $contactEmail ?></p>
            <hr>
            <h3>お問い合わせの種類</h3>
            <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
            <?php
                $contactKinds = $this->assign['contactKinds'];
                $contactKindsMap = [
                    1 => '広告・レシピ掲載',
                    2 => '情報の不具合・修正',
                    3 => '退会希望',
                    9 => 'その他'
                ];
            ?>
            <p><?= $contactKindsMap[$contactKinds] ?></p>
            <hr>
            <h3>メッセージ</h3>
            <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
            <?php $contactMessage = $this->assign['contactMessage']; ?>
            <p><?= $contactMessage ?></p>
        </div>

        <div class="contact_submit">
            <button class="edit_button" onclick="location.href='contact.php'">編集</button>
            <button class="submit_button" onclick="location.href='main.php'">送信</button>
        </div>
    </main>