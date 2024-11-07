<main>
    <div class="choiceFoods">
        <h2>リンク切れrecipe一覧</h2>
    </div>
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
                <!-- 他の行も追加 -->
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