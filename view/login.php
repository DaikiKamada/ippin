<main>
    <h2 class="login_form_title">ログイン</h2>
    <div class="login_form_box container">
        <form action="manageTop.php" method="post" class="login_form" id="loginForm">
            <label for="userId">ユーザーID</label>
            <input type="text" id="userId" name="userId" required pattern="\d{1,11}" title="ユーザーIDは1～11桁の数字で入力してください">
            
            <label for="mailAddress">メールアドレス</label>
            <input type="email" id="mailAddress" name="mailAddress" required>
            
            <button type="submit" id="submitBtn" disabled>ログイン</button>
        </form>
    </div>
</main>