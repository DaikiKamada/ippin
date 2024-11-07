<main>
        <h2 class="contact_form_title">お問い合わせ</h2>
        <div class="contact_form_box container">
            <form action="contactConfirm.php" method="post" class="contact_form">
                <label for="name">お名前</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" required>
                
                <label for="kinds">種類</label>
                <select id="kinds" name="kinds" required>
                    <option value="" disabled selected>-- 選択してください --</option>
                    <option value="1">広告・レシピ掲載</option>
                    <option value="2">情報の不具合・修正</option>
                    <option value="3">退会希望</option>
                    <option value="9">その他</option>
                </select>
                
                <label for="message">メッセージ</label>
                <textarea id="message" name="message" rows="4" required></textarea>
                
                <p>※お問い合わせは、担当者の気が向いたときにご返信いたします。</p>
                <p>※迷惑メールの設定により、メールが受信できない場合がございます。「@gmail.com」をドメイン受信設定してください。</p>
                <p>※個人情報の取り扱いに関しましては、<a class="contact_a" href="tos.php">利用規約</a>・<a class="contact_a" href="pp.php">プライバシーポリシー</a>をお読みの上、同意して送信してください。</p>
                
                <label for="terms">
                    <input type="checkbox" id="terms" name="terms" required>
                    利用規約に同意します
                </label>

                <button type="submit" id="submitBtn" disabled>送信</button>
            </form>
        </div>
    </main>