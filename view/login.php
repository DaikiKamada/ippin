<main>
    <h2 class="login_form_title">ログイン</h2>
    <div class="login_form_box container">
        <form action="manageTop.php" method="post" class="login_form">
            <label for="name">お名前</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">メールアドレス</label>
            <input type="email" id="email" name="email" required>
            
            <button type="submit" id="submitBtn" disabled>ログイン</button>
        </form>
    </div>
</main>