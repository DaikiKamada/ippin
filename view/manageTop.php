<main>
    <!-- 三角形のレイアウト -->
    <div class="triangle_container">
        <h1 class="top">recipe数：n件</h1>
        <h1 class="left">表示：n件</h1>
        <h1 class="right">非表示：n件</h1>
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
                <label for="options">選択してください：</label>
                <select multiple="multiple" id="ingredient_select">
                    <option value="1">みかん</option>
                    <option value="2">キャベツ</option>
                    <option value="3">タマゴ</option>
                    <option value="4">タマネギ</option>
                    <option value="5">レタス</option>
                </select>
                <br>
                <div class="radio-group">
                    <input type="radio" id="all" name="show" value="all" checked/>
                    <label for="all">全て</label>
                    <input type="radio" id="show" name="show" value="show" />
                    <label for="show">表示</label>
                    <input type="radio" id="hide" name="show" value="hide" />
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

<!-- なかったらダメ -->
<script>
    $(function () {
        $('#ingredient_select').multipleSelect({
            width: '300px',
            selectAll: false,
            onClick: function(view) {
                const selectedOptions = $('#ingredient_select').multipleSelect('getSelects');
                if (selectedOptions.length > 3) {
                    alert('最大3つまで選択できます。');
                    $('#ingredient_select').multipleSelect('setSelects', selectedOptions.slice(0, 3));
                }
            }
        });

        // ラジオボタンの状態が変わったときに案内文を消す
        $('input[name="show"]').on('change', function() {
            const placeholderOption = $('#ingredient_select').find('option.placeholder');
            if (placeholderOption.length) {
                placeholderOption.remove();
            }
        });
    });
</script>
