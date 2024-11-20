<main>
    <h2 class="login_form_title">ログイン</h2>
    <div class="login_form_box container">
        <form action="manageTop.php" method="post" id="loginForm" class="login_form">            
            <label for="mailAddress">メールアドレス</label>
            <input type="email" id="mailAddress" name="mailAddress" required title="有効なメールアドレスを入力してください">

            <label for="userId">パスワード</label>
            <input type="password" id="password" name="password" required pattern="" title="パスワードは半角英数・記号のみ使用できます">            
            <button type="submit" id="submitBtn" disabled>ログイン</button>
        </form>
    </div>
</main>