<main class="test">
    <div class="ippin_select_box container text-center">
        <h2 class="ippin_result_title">つくれるippin</h2>
        <p class="ippin_result_text">選択中の食材</p>
        <div class="select_foodValeus_box">
            <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
            <?php $foodsName = $this->assign['foodsName']; ?>
            <!-- $foodsNameに値が入っていればタグを生成、なければpタグを生成 -->
            <?php if (!empty($foodsName)): ?>
                <?php foreach ($foodsName as $name) { ?>
                    <button class="select_foodValeus_tag"><?= $name ?></button>
                <?php } ?>
            <?php else: ?>
                <p>食材が選択されていません！</p>
            <?php endif; ?>
        </div>
        <p class="ippin_result_annotation">※下のメニューをクリックするとレシピページにジャンプします</p>
    </div>

    <div class="container">
        <div class="row">
            <?php
                // $vAry[]にrecipeListがあれば$recipeListに配列を渡す、なければ空の配列を生成
                if (isset($vAry['recipeList'])) {
                    $recipeList = $vAry['recipeList'];
                } else {
                    $recipeList = [];
                }
            ?>
            <?php foreach ($recipeList as $index => $r) { ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card">
                        <a href="<?= $r['url']?>" target="_blank">
                            <div class="image-container">
                                <img src="images/<?= $r['img']?>" class="card-img" alt="<?= $r['recipeName']?>の画像">
                            </div>
                            <div class="card-body">
                                <p class="card-siteName">
                                    出典：
                                    <?= $r['siteName']?>
                                </p>
                                <h5 class="card-recipeName">
                                    <?= $r['recipeName']?>
                                </h5>
                                <p class="card-foodValues">
                                    <?php
                                        // 現在のレシピに対応するfoodNameArrayのデータを取得
                                        if (isset($vAry['foodNameArray'][$index]) && is_array($vAry['foodNameArray'][$index])) {
                                            foreach ($vAry['foodNameArray'][$index] as $foodName) {
                                                echo '<button class="foodValues_tag">'.$foodName.'</button>';
                                            }
                                        }
                                    ?>                                 
                                </p>
                                <p class="card-memo">
                                    補足：
                                    <?= $r['memo']?>
                                </p>
                                <p class="card-comment">
                                    コメント：
                                    <?= $r['comment']?>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
            <?php } ?>

            <!-- ↓↓↓↓↓広告用カード↓↓↓↓↓ -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <a href="https://www.maruchan.co.jp/products/search/nabeyou_ramen.html" target="_blank">
                        <div class="image-container">
                            <img src="images/marukomen.png" class="card-img" alt="広告の画像">
                        </div>
                        <div class="card-body">
                            <p class="card-siteName">
                                広告：東洋水産
                            </p>
                            <h5 class="card-recipeName">
                                鍋用ラーメン２袋入り
                            </h5>
                            <p class="card-foodValues">
                                <button class="foodValues_tag">ラーメン</button>
                            </p>
                            <p class="card-memo">
                                みんな大好き！鍋といえばコレで決まり！ちびまる子ちゃんの鍋用ラーメン！
                            </p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="card">
                    <a href="https://item.rakuten.co.jp/kanidokoro/628/?iasid=07rpp_10095___en-m40k8kok-4y-e664dc93-af42-4304-8c1f-e0f4415b5fee" target="_blank">
                        <div class="image-container">
                            <img src="images/kani.jpg" class="card-img" alt="広告の画像">
                        </div>
                        <div class="card-body">
                            <p class="card-siteName">
                                広告：楽天市場
                            </p>
                            <h5 class="card-recipeName">
                                大感謝かにジャンボ福袋
                            </h5>
                            <p class="card-foodValues">
                                <button class="foodValues_tag">カニ</button>
                                <button class="foodValues_tag">福袋</button>
                            </p>
                            <p class="card-memo">
                                必ず得する！はずれなし！1,801円から、最大22,801円お得！！
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            <!-- ↑↑↑↑↑広告用カード↑↑↑↑↑ -->
        </div>
    </div>
</main>