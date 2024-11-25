<main>
    <h1>削除確認</h1>
    <hr>
    
    <form action="recipeDeleteCheck.php" method="POST" id="deleteForm">
        <div class="d_recipe_containor">
            <table class="d_recipe">
                <tr>
                    <th>recipe名</th>
                    <th>食材</th>
                    <th>コメント</th>
                    <th>補足</th>
                    <th>出典元</th>
                    <th>最終更新日</th>
                    <th>表示設定</th>
                </tr>
                <?php
                    // $vAry[]にfoodsListがあれば$foodsListに配列を渡す、なければ空の配列を生成
                    if (isset($vAry['deleteRecipe'])) {
                        $deleteRecipe = $vAry['deleteRecipe'];
                    } else {
                        $deleteRecipe = [];
                    }
                    if (isset($vAry['foodsList'])) {
                        $foodsList = $vAry['foodsList'];
                    } else {
                        $foodsList = [];
                    }
                    
                ?>
                <?php foreach ($deleteRecipe as $d) { ?>
                    <tr>
                        <td><?= $d['recipeName'] ?></td>
                        <td><?php
                            foreach($foodsList as $key => $value) {
                                print $value['foodName'];
                                if($value != end($foodsList)) {
                                    print '・';
                                }
                            } ?></td>
                        <td><?= $d['comment'] ?></td>
                        <td><?= $d['memo'] ?></td>
                        <td><?= $d['siteName'] ?></td>
                        <td><?= $d['lastUpdate'] ?></td>
                        <td><?= $d['recipeFlag'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>

        <div class="r_deleteCheck">
            <h2>選択したrecipeを削除する場合は下記に「削除」と入力してください。</h2>
            <input type="text" id="deleteInput" placeholder="削除"><br>
            <!-- <button type="button" onclick="location.href='recipeManagement.php'">キャンセル</button> -->
            <button class="edit" type="submit" name="delete" value="cancel">キャンセル</button>
            <button class="delete" type="submit" name="delete" value="delete">削除</button>
        </div>
    </form>
</main>