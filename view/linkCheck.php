<main>
    <div class="choiceFoods">
        <h2>リンク切れrecipe一覧</h2>
    </div>
    <hr>

    <form id="url" method="post">
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
                    <th>URL</th>
                    <th>出典元</th>
                    <th>最終更新日</th>
                    <th>表示設定</th>
                </tr>

                <?php
                if (isset($vAry['noLinkRecipeList'])) {
                    $noLinkRecipeList = $vAry['noLinkRecipeList'];
                } else {
                    $noLinkRecipeList = [];
                }
                ?>
                <?php
                    for ($i = 0; $i < count($noLinkRecipeList); $i++) {
                ?>
                    <tr>
                        <td><input type="checkbox" id="url<?= $noLinkRecipeList[$i]['recipeId'] ?>" name="choicedRecipe[]" value="<?= $noLinkRecipeList[$i]['recipeId'] ?>"></td>
                        <td><?=$noLinkRecipeList[$i]['recipeName']?></td>
                        <td><?=$noLinkRecipeList[$i]['url']?></td>
                        <td><?=$noLinkRecipeList[$i]['siteName']?></td>
                        <td><?=$noLinkRecipeList[$i]['lastUpdate']?></td>
                        <td><?=$noLinkRecipeList[$i]['recipeFlag'] == 0? '非表示':'表示'?></td>
                    </tr>
                <?php } ?>
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
                            <button class="edit" onclick=submitClick() data-action="lcRecipeEdit.php">編集</button>
                            <button class="delete" onclick=submitClick() data-action="lcRecipeDeleteCheck.php">削除</button>
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

<script>
    function submitClick(){
        let elm       = event.target;
        let actionUrl = elm.getAttribute("data-action");
        document.getElementById("url").action = actionUrl;
    }
</script>