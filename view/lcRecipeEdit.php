<main>
    <h1>リンク切れレシピの編集</h1>
    <p>※「リンク先URL」「出典元」「表示設定」以外の項目については<br>
    レシピの編集画面から更新してください。</p>
    <hr>
    <form action="lcRecipeEdit.php" method="post" class="recipeEdit"  enctype="multipart/form-data">
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
                    $recipeId = $editedRecipe[$i]['recipeId'];
                    $recipeName = $editedRecipe[$i]['recipeName'];
                    $comment = $editedRecipe[$i]['comment'];
                    $url = $editedRecipe[$i]['url'];
                    $siteName = $editedRecipe[$i]['siteName'];
                    $recipeFlag = $editedRecipe[$i]['recipeFlag'];
                    $isCheckedShow = $recipeFlag == 1 ? 'checked' : '';
                    $isCheckedHide = $recipeFlag == 0 ? 'checked' : '';

                    // $recipeName = isset($this->assign['editedRecipe'][$i]['recipeName']) ? $this->assign['editedRecipe'][$i]['recipeName'] : '';
                    // $comment = isset($this->assign['editedRecipe'][$i]['comment']) ? $this->assign['editedRecipe'][$i]['comment'] : '';
                    // $url = isset($this->assign['editedRecipe'][$i]['url']) ? $this->assign['editedRecipe'][$i]['url'] : '';
                    // $siteName = isset($this->assign['editedRecipe'][$i]['siteName']) ? $this->assign['editedRecipe'][$i]['siteName'] : '';
                    // $recipeFlag = isset($this->assign['editedRecipe'][$i]['recipeFlag']) ? $this->assign['editedRecipe'][$i]['recipeFlag'] : '';
                    // $isCheckedShow = $recipeFlag == 1 ? 'checked' : '';
                    // $isCheckedHide = $recipeFlag == 0 ? 'checked' : '';

                    // $foodValues = isset($this->assign['editedRecipe'][$i]['foodValues']) ? $this->assign['editedRecipe'][$i]['foodValues'] : [];
                    // $selectedFoodValues = explode('#', trim($foodValues, '#'));
                    // $howtoId = isset($this->assign['editedRecipe'][$i]['howtoId']) ? $this->assign['editedRecipe'][$i]['howtoId'] : '';
                    // $memo = isset($this->assign['editedRecipe'][$i]['memo']) ? $this->assign['editedRecipe'][$i]['memo'] : '';

                ?>
                
                <input id="block-<?= $i ?>" type="checkbox" class="toggle" name="<?= $i ?>[recipeId]" value="<?= $recipeId ?>">
                <label class="Label" for="block-<?= $i ?>"><?= $recipeName ?></label>
                <div class="edit_containor">
                    <div>
                        <table>
                            <tr>
                                <th>recipe名</th>
                                <th>コメント</th>
                                <th>recipeリンク</th>
                                <th>出典元</th>
                                <th>表示設定</th>
                            </tr>

                            <tr>
                                <td><?= $recipeName ?></td>
                                <td><?= $comment ?></td>
                                <td><input type="text" name="<?= $i ?>[url]" value="<?= $url ?>" required></td>
                                <td><input type="text" name="<?= $i ?>[siteName]" value="<?= $siteName ?>" required></td>
                                <td><input type="radio" id="show" name="<?= $i ?>[recipeFlag]" value="show" <?= $isCheckedShow ?>>
                                    <label for="show">表示</label>
                                    <input type="radio" id="hide" name="<?= $i ?>[recipeFlag]" value="hide" <?= $isCheckedHide ?>>
                                    <label for="hide">非表示</label>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
            <?php } ?>
        </section>
        
        <div class="editCheck">
            <!-- <button class="edit" type="button" name="update" value="cancel" onclick="location.href='recipeManagement.php'">キャンセル</button> -->
            <button class="edit" type="submit" name="update" value="cancel">キャンセル</button>
            <button class="delete" type="submit" name="update" value="update">変更</button>
        </div>
    </form>
</main>