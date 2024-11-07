<header>
    <!-- ハンバーガーメニュー -->
    <div class="header__inner">
        <button id="js-hamburger" type="button" class="hamburger" aria-controls="navigation" aria-expanded="false" aria-label="メニューを開く">
            <span class="hamburger__line"></span>
            <span class="hamburger__text"></span>
        </button>
        <div class="header__nav-area js-nav-area" id="navigation">
            <nav id="js-global-navigation" class="global-navigation">
                <ul class="global-navigation__list">
                    <li><a href="contact.php" class="global-navigation__link btn-flat2"><span>お問い合わせ</span></a></li>
                    <li><a href="tos.php" class="global-navigation__link btn-flat2"><span>利用規約</span></a></li>
                    <li><a href="pp.php" class="global-navigation__link btn-flat2"><span>プライバシーポリシー</span></a></li>
                </ul>
                <div class="butasan_box">
                    <img class="butasan" src="images/pig.png" alt="マスコットキャラクター">
                    <p class="b_text">ー ippin ー</p>
                </div>
                <div id="js-focus-trap" tabindex="0"></div>
            </nav>
        </div>
    </div>

    <!-- ヘッダーロゴ -->
    <a href="main.php"><h1 class="header_center"><img class="header_logo" src="images/logo_banner.png" alt="ippin|ロゴ"></h1></a>

    <!-- ログインボタン -->
    <button class="header_right">
        <a href="login.php">
            <img class="login_logo" src="images/login.png" alt="ログイン">
            <p class="login_text">ログイン</p>
        </a>
    </button>
</header>