<main>
    <h1>recipe編集</h1>
    <hr>
    <form action="recipeEdit.php" method="post" class="recipeEdit">
        <section class="accordion">
            <?php
            if (isset($vAry['editedRecipe'])) {
                $editedRecipe = $vAry['editedRecipe'];
            }
            else {
                $editedRecipe = [];
            }
            ?>
            <?php
            for($i = 0; $i < count($editedRecipe); $i++) {
                ?><input id="block-<?= $i ?>" type="checkbox" class="toggle" name="<?= $i ?>[recipeId]" value="<?= $editedRecipe[$i]['recipeId'] ?>">
                <label class="Label" for="block-<?= $i ?>"><?= $editedRecipe[$i]['recipeName'] ?></label>
                <div class="edit_containor">
                    <div>
                        <label>recipe名：</label>
                        <input type="text" name="<?= $i ?>[recipeName]">
                    </div>
                    
                    <div class="dropdown">
                        <button type="button" id="dropdownButton">食材を選択（3つまで）</button>
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
                                    <input type="checkbox" id="foods<?= $f['foodId'] ?>" name="<?= $i ?>[foodValues][]" value="<?= $f['foodId'] ?>" data-food-name="<?= e($f['foodName']) ?>" onclick="limitCheckboxes(this)"><?= $f['foodName'] ?>
                                </label>
                            <?php }?>
                        </div>
                    </div>

                    <div>
                        <label>調理方法：</label>
                        <select name="<?= $i ?>[howtoId]">
                            <option value="1">焼く</option>
                            <option value="2">煮る</option>
                            <option value="3">揚げる</option>
                        </select>
                    </div>

                    <div class="full-width">
                        <label>コメント：</label>
                        <textarea name="<?= $i ?>[comment]"></textarea>
                    </div>
                    
                    <div>
                        <label>補足：</label>
                        <input type="text" name="<?= $i ?>[memo]">
                    </div>
                    
                    <div>
                        <label>recipe画像をアップロード</label>
                        <input type="file" name="<?= $i ?>[img]">
                    </div>
                    
                    <div>
                        <label>recipeリンク：</label>
                        <input type="text" name="<?= $i ?>[url]" >
                    </div>

                    <div>
                        <label>出典元：</label>
                        <input type="text" name="<?= $i ?>[siteName]">
                    </div>

                    <div>
                        <label>表示設定：</label>
                        <div>
                            <input type="radio" id="show" name="<?= $i ?>[recipeFlag]" value="show" checked/>
                            <label for="show">表示</label>
                            <input type="radio" id="hide" name="<?= $i ?>[recipeFlag]" value="hide" />
                            <label for="hide">非表示</label>
                        </div>
                    </div>
                </div>
                <?php } ?>
            <hr>
        </section>
        
        <hr>

        <div class="editCheck">
            <button class="edit" type="button" name="update" value="cancel" onclick="location.href='recipeManagement.php'">キャンセル</button>
            <button class="delete" type="submit" name="update" value="update">変更</button>
        </div>
    </form>
</main>