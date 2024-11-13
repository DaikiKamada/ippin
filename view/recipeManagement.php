<main>
    <div class="choiceFoods">
        <h2>選択中の食材：レタス　タマゴ　キャベツ</h2>
    </div>

    <hr>

    <form action="#" method="post" class="newRecipe">
        <div>
            <label>recipe名：</label>
            <input type="text">
        </div>
        
        <div>
            <label for="ingredient_select">選択してください：</label>
            <select multiple="multiple" id="ingredient_select">
                <option value="1">みかん</option>
                <option value="2">キャベツ</option>
                <option value="3">タマゴ</option>
                <option value="4">タマネギ</option>
                <option value="5">レタス</option>
            </select>
        </div>

        <div>
            <label>調理方法：</label>
            <select name="how">
                <option value="1">焼く</option>
                <option value="2">煮る</option>
                <option value="3">揚げる</option>
            </select>
        </div>

        <div>
            <label>表示設定：</label>
            <div>
                <input type="radio" id="show" name="show" value="show" checked/>
                <label for="show">表示</label>
                <input type="radio" id="hide" name="show" value="hide" />
                <label for="hide">非表示</label>
            </div>
        </div>

        <div class="full-width">
            <label>メニューの説明：</label>
            <textarea name="explanation"></textarea>
        </div>

        <div>
            <label>補足：</label>
            <input type="text" name="supplement">
        </div>

        <div>
            <label>recipe画像をアップロード</label>
            <input type="file" name="upfile">
        </div>

        <div>
            <label>recipeリンク：</label>
            <input type="text" name="recipeLink" id="">
        </div>

        <div>
            <label>出典元：</label>
            <input type="text">
        </div>

        <div class="full-width">
            <button class="rmButton" type="submit">追加</button>
        </div>
    </form>

    <hr>

    <form id="url" method="POST">
        <div class="recipe_containor">
            <table class="recipe">
                <tr>
                    <th>
                        <div class="checkbox-container">
                            <p class="mini">すべて✓</p>
                            <input type="checkbox" id="selectAll">
                        </div>
                    </th>
                    <th>recipeID</th>
                    <th>recipe名</th>
                    <th>最終更新日</th>
                    <th>表示設定</th>
                </tr>

                <!-- サンプル行 -->
                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>1</td>
                    <td>トマト煮込み</td>
                    <td>2024/10/25</td>
                    <td>表示</td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>2</td>
                    <td>ハンバーガー</td>
                    <td>2024/10/26</td>
                    <td>表示</td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>3</td>
                    <td>もつ鍋</td>
                    <td>2024/10/27</td>
                    <td>表示</td>
                </tr>
            </table>
        </div>
                
        <!-- アコーディオン -->
        <div class="accordion fixed-bottom" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        <span id="selectedCount">0</span>件選択中
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <ul id="check_items">
                        </ul>
                        <button type="submit" onclick="setAction('recipeEdit.php')">編集</button>
                        <button type="submit" onclick="setAction('recipeDeleteCheck.php')">削除</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
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