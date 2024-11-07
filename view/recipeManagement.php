<main>
    <div class="choiceFoods">
        <h2>選択中の食材：レタス　タマゴ　キャベツ</h2>
    </div>

    <hr>

    <form action="#" method="post" class="newRecipe">
        <div>
            recipe名：<input type="text">
            調理方法：<select name="how">
                <option value="1">焼く</option>
                <option value="2">煮る</option>
                <option value="3">揚げる</option>
            </select>
            表示：
            <input type="radio" id="show" name="show" value="show" checked/>
            <label for="show">有効</label>
            <input type="radio" id="hide" name="show" value="hide" />
            <label for="hide">無効</label>
        </div>
        <div>
            recipe画像をアップロード<input type="file" name="upfile">
        </div>
        <div>
            メニューの説明：<textarea name="explanation"></textarea>
        </div>
        <div>
            補足：<input type="text" name="supplement">
        </div>
        <div>
            recipeリンク：<input type="text" name="recipeLink" id="">
        </div>
        <button type="submit">追加</button>
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
                    <th>表示</th>
                </tr>

                <!-- サンプル行 -->
                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>1</td>
                    <td>トマト煮込み</td>
                    <td>2024/10/25</td>
                    <td>有効</td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>2</td>
                    <td>ハンバーガー</td>
                    <td>2024/10/26</td>
                    <td>有効</td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>3</td>
                    <td>もつ鍋</td>
                    <td>2024/10/27</td>
                    <td>有効</td>
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