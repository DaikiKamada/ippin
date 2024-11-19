<main>
    <div class="choiceFoods">
        <h2>選択中の食材：レタス　タマゴ　キャベツ</h2>
    </div>

    <hr>

    <form action="recipeManagement.php" method="post" class="newRecipe">
        <div>
            <label>recipe名：</label>
            <input type="text" name="recipeName">
        </div>
        
        <div>
            <label>調理方法：</label>
            <select name="howtoId">
                <option value="1">焼く</option>
                <option value="2">煮る</option>
                <option value="3">揚げる</option>
            </select>
        </div>

        <div class="full-width">
            <label>コメント：</label>
            <textarea name="comment"></textarea>
        </div>
        
        <div>
            <label>補足：</label>
            <input type="text" name="memo">
        </div>
        
        <div>
            <label>recipe画像をアップロード</label>
            <input type="file" name="img">
        </div>
        
        <div>
            <label>recipeリンク：</label>
            <input type="text" name="url" >
        </div>

        <div>
            <label>出典元：</label>
            <input type="text" name="siteName">
        </div>
    
        <div>
            <label>表示設定：</label>
            <div>
                <input type="radio" id="show" name="recipeFlag" value="show" checked/>
                <label for="show">表示</label>
                <input type="radio" id="hide" name="recipeFlag" value="hide" />
                <label for="hide">非表示</label>
            </div>
        </div>

        <div class="full-width">
            <button class="rmButton" type="submit" name="insert">追加</button>
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
                    <th>recipe名</th>
                    <th>食材</th>
                    <th>コメント</th>
                    <th>補足</th>
                    <th>出典元</th>
                    <th>最終更新日</th>
                    <th>表示設定</th>
                </tr>

                <!-- サンプル行 -->
                <tr>
                    <td><input type="checkbox" name="choice"></td>
                    <td>トマト煮込み</td>
                    <td>トマト</td>
                    <td>トマトを煮込んだ料理</td>
                    <td>あれでも代用可</td>
                    <td>kmdpad</td>
                    <td>2024/10/25</td>
                    <td>表示</td>
                </tr>

                <tr>
                    <td><input type="checkbox" name="choice"></td>
                <?php 
                foreach($result as $x) {
                    ?> <td><?=$result[$x]['recipeName']?></td> <?php
                    ?> <td>トマト</td> <?php
                    ?> <td><?=$result[$x]['comment']?></td> <?php
                    ?> <td><?=$result[$x]['memo']?></td> <?php
                    ?> <td><?=$result[$x]['siteName']?></td> <?php
                    ?> <td><?=$result[$x]['lastUpdate']?></td> <?php
                    ?> <td><?=$result[$x]['flag']?></td> <?php
                }
                    ?>
                </tr>
                <tr>
                    <!-- <td><input type="checkbox" name="choice"></td> -->
                    <!-- <td>トマト煮込み</td>
                    <td>トマト</td>
                    <td>トマトを煮込んだ料理</td>
                    <td>あれでも代用可</td>
                    <td>kmdpad</td>
                    <td>2024/10/25</td>
                    <td>表示</td> -->
                </tr>

                <tr>
                    <!-- <td><input type="checkbox" name="choice"></td> -->
                    <!-- <td>トマト煮込み</td>
                    <td>トマト</td>
                    <td>トマトを煮込んだ料理</td>
                    <td>あれでも代用可</td>
                    <td>kmdpad</td>
                    <td>2024/10/25</td>
                    <td>表示</td> -->
                </tr>
            </table>
        </div>
                
        <!-- アコーディオン -->
        <div class="accordion fixed-bottom" id="accordionPanelsStayOpenExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <div class="accordion-header-content">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                            <span id="selectedCount">0</span> 件選択中
                        </button>
                        <div class="action-buttons">
                            <button type="submit" class="r_edit" onclick="setAction('recipeEdit.php')">編集</button>
                            <button type="submit" class="r_delete" onclick="setAction('recipeDeleteCheck.php')">削除</button>
                        </div>
                    </div>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show">
                    <div class="accordion-body">
                        <ul id="check_items">
                            <!-- 選択したアイテムリストがここに表示されます -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
