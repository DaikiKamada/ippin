<main>
    <h1>recipe編集</h1>
    <hr>
    <form action="recipeEdit.php" method="post" class="recipeEdit" enctype="multipart/form-data">
        <section class="accordion">
            <?php
                if (isset($vAry['editedRecipe'])) {
                    $editedRecipe = $vAry['editedRecipe'];
                }
                else {
                    $editedRecipe = [];
                }                
            ?>
            <?php for($i = 0; $i < count($editedRecipe); $i++) { ?>
                <!-- Viewクラスのインスタンスを直接参照しに行く（？） -->
                <?php
                    $recipeName = isset($this->assign['editedRecipe'][$i]['recipeName']) ? $this->assign['editedRecipe'][$i]['recipeName'] : '';
                    $foodValues = isset($this->assign['editedRecipe'][$i]['foodValues']) ? $this->assign['editedRecipe'][$i]['foodValues'] : [];
                    $selectedFoodValues = explode('#', trim($foodValues, '#'));
                    $howtoId = isset($this->assign['editedRecipe'][$i]['howtoId']) ? $this->assign['editedRecipe'][$i]['howtoId'] : '';
                    $comment = isset($this->assign['editedRecipe'][$i]['comment']) ? $this->assign['editedRecipe'][$i]['comment'] : '';
                    $memo = isset($this->assign['editedRecipe'][$i]['memo']) ? $this->assign['editedRecipe'][$i]['memo'] : '';
                    $url = isset($this->assign['editedRecipe'][$i]['url']) ? $this->assign['editedRecipe'][$i]['url'] : '';
                    $siteName = isset($this->assign['editedRecipe'][$i]['siteName']) ? $this->assign['editedRecipe'][$i]['siteName'] : '';
                    $recipeFlag = isset($this->assign['editedRecipe'][$i]['recipeFlag']) ? $this->assign['editedRecipe'][$i]['recipeFlag'] : '';
                    $isCheckedShow = ($recipeFlag === '表示') ? 'checked' : '';
                    $isCheckedHide = ($recipeFlag === '非表示') ? 'checked' : '';
                ?>
                
                <input id="block-<?= $i ?>" type="checkbox" class="toggle" name="<?= $i ?>[recipeId]" value="<?= $editedRecipe[$i]['recipeId'] ?>">
                <label class="Label" for="block-<?= $i ?>"><?= $editedRecipe[$i]['recipeName'] ?></label>
                <div class="edit_containor">
                    <div>
                        <label>recipe名：</label>
                        <input type="text" name="<?= $i ?>[recipeName]" value="<?= $recipeName ?>" required>
                    </div>
                    
                    <div class="dropdown" id="dropdown-<?= $i ?>">
                        <button type="button" id="dropdownButton-<?= $i ?>">食材を選択（3つまで）</button>
                        <div class="dropdown-content">
                            <?php
                                // $vAry[]にfoodsListがあれば$foodsListに配列を渡す、なければ空の配列を生成
                                if (isset($vAry['allFoodsList'])) {
                                    $allFoodsList = $vAry['allFoodsList'];
                                } else {
                                    $allFoodsList = [];
                                }
                            ?>
                            <?php foreach ($allFoodsList as $f) { ?>                            
                                <label for="foods<?= $f['foodId'] ?>">
                                    <input
                                        type="checkbox"
                                        id="foods<?= $f['foodId'] ?>"
                                        name="<?= $i ?>[foodValues][]"
                                        value="<?= $f['foodId'] ?>"
                                        data-food-name="<?= e($f['foodName']) ?>"
                                        onclick="limitCheckboxes(this)"
                                        <?php
                                            if (in_array($f['foodId'], $selectedFoodValues)) {
                                                echo 'checked'; 
                                            }
                                        ?>
                                    >
                                    <?= $f['foodName'] ?>
                                </label>
                            <?php }?>
                        </div>
                    </div>

                    <div>
                        <label>調理方法：</label>
                        <select name="<?= $i ?>[howtoId]">
                            <option value="" disabled <?= empty($howtoId) ? 'selected' : '' ?>>-- 選択してください --</option>
                            <option value="1">焼く</option>
                            <option value="2">煮る</option>
                            <option value="3">揚げる</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label>コメント：</label>
                        <textarea name="<?= $i ?>[comment]" required><?= $comment ?></textarea>
                    </div>
                    
                    <div>
                        <label>補足：</label>
                        <input type="text" name="<?= $i ?>[memo]" value="<?= $memo ?>" required>
                    </div>
                    
                    <div>
                        <label>recipe画像をアップロード</label>
                        <input type="file" name="<?= $i ?>upFile">
                    </div>
                    
                    <div>
                        <label>recipeリンク：</label>
                        <input type="text" name="<?= $i ?>[url]" value="<?= $url ?>" required>
                    </div>

                    <div>
                        <label>出典元：</label>
                        <input type="text" name="<?= $i ?>[siteName]" value="<?= $siteName ?>" required>
                    </div>

                    <div>
                        <label>表示設定：</label>
                        <div>
                            <input type="radio" id="show" name="<?= $i ?>[recipeFlag]" value="show" <?= $isCheckedShow ?> />
                            <label for="show">表示</label>
                            <input type="radio" id="hide" name="<?= $i ?>[recipeFlag]" value="hide" <?= $isCheckedHide ?> />
                            <label for="hide">非表示</label>
                        </div>
                    </div>
                </div>
                <hr>
            <?php } ?>
        </section>
        
        <div class="editCheck">
            <button class="edit" type="button" name="update" value="cancel" onclick="location.href='recipeManagement.php'">キャンセル</button>
            <button class="delete" type="submit" name="update" value="update">変更</button>
        </div>
    </form>
</main>