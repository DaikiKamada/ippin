<main>
    <!-- 三角形のレイアウト -->
    <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
    <?php
        $countRecipeAll = $this->assign['countRecipeAll'];
        $countRecipeOn = $this->assign['countRecipeOn'];
        $countRecipeOff = $this->assign['countRecipeOff'];
    ?>
    <div class="triangle_container">
        <h1 class="top">
            recipe数：<?= $countRecipeAll ?>件
        </h1>
        <h1 class="left">
            表示：<?= $countRecipeOn ?>件
        </h1>
        <h1 class="right">
            非表示：<?= $countRecipeOff ?>件
        </h1>
    </div>

    <!-- 中央に配置する各種ボタン -->
    <div class="center-container">
        <div class="flex_row">
            <h2 class="mTh2">食材設定</h2>
            <button type="button" onclick="location.href='foodsManagement.php'" class="button-link">食材マスタへ</button>
        </div>

        <div class="flex_row">
            <h2 class="mTh2">recipe設定</h2>
            <form action="recipeManagement.php" method="post" class="mTform">
                <div class="dropdown">
                    <button type="button" id="dropdownButton">食材を選択（3つまで）</button>
                    <div class="dropdown-content">
                        <?php
                            // $vAry[]にfoodsListがあれば$foodsListに配列を渡す、なければ空の配列を生成
                            if (isset($vAry['foodsList'])) {
                                $foodsList = $vAry['foodsList'];
                            } else {
                                $foodsList = [];
                            }
                        ?>
                        <?php foreach ($foodsList as $f) { ?>
                            <label><input type="checkbox" value="<?= $f['foodId'] ?>:<?= $f['foodName'] ?>" onclick="limitCheckboxes(this)"><?= $f['foodName'] ?></label>
                        <?php } ?>
                    </div>
                </div>
                <br>
                <div class="radio-group">
                    <input type="radio" id="all" name="show" value="9" checked/>
                    <label for="all">全て</label>
                    <input type="radio" id="show" name="show" value="1" />
                    <label for="show">表示</label>
                    <input type="radio" id="hide" name="show" value="0" />
                    <label for="hide">非表示</label>
                </div>
                <button type="submit" class="button-link">検索</button>
            </form>
        </div>

        <div class="flex_row">
            <h2 class="mTh2">リンク切れチェック</h2>
            <button type="button" onclick="location.href='linkCheck.php'" class="button-link">実行</button>
        </div>
    </div>
</main>