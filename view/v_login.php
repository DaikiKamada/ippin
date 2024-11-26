<main>
    <h2 class="login_form_title">ログイン</h2>
    <div class="login_form_box container">
        <form action="manageTop.php" method="post" id="loginForm" class="login_form">            
            <label for="mailAddress">メールアドレス</label>
            <input type="email" id="mailAddress" name="mailAddress" required title="有効なメールアドレスを入力してください">

            <label for="password">パスワード</label>
            <input type="password" id="password" name="password" required 
                pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{3,20}$"
                minlength="3" maxlength="20"
                title="パスワードは半角英字、数字、記号を含む3〜20文字で入力してください">
            <button type="submit" id="submitBtn" disabled>ログイン</button>
        </form>
    </div>
</main>