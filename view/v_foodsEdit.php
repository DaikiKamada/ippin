<main>
    <h1>食材編集</h1>
    <hr>
    <form id="foodForm" action="foodsEdit.php" method="POST">
        <div class="data_containor">
            <table class="data" border="1">
                <tr><th>ID</th><th>食材</th><th>カテゴリ</th><th>recipe件数</th><th>表示</th><th>非表示</th></tr>
                <!-- 食材名を表示 -->
                <?php
                    if (isset($vAry['editInfo'])) {
                        $editInfo = $vAry['editInfo'];
                    } else {
                        $editInfo = [];
                    }
                ?>
                <tr>
                    <td><?=$editInfo['foodId']?></td>
                    <td><input type="text" name="foodName" value="<?=$editInfo['foodName']?>" class="tdinput"></td>
                    <td><select name="foodCatId" value="<?=$editInfo['catName']?>">
                        <!-- 食材カテゴリを表示 -->
                        <?php
                            if (isset($vAry['foodCatM'])) {
                                $foodCatM = $vAry['foodCatM'];
                            } else {
                                $foodCatM = [];
                            }
                        ?>
                        <?php for($i = 0; $i < count($foodCatM); $i++) { 
                            if ($foodCatM[$i]['catName'] == $editInfo['catName']) {?>
                            <option value="<?=$foodCatM[$i]['foodCatId']?>" selected><?=$foodCatM[$i]['catName']?></option>
                        <?php } else {?>
                            <option value="<?=$foodCatM[$i]['foodCatId']?>"><?=$foodCatM[$i]['catName']?></option>
                        <?php } } ?>
                    </select></td>
                    <td><?=$editInfo['recipe_count']?></td>
                    <td><?=$editInfo['flag1_count']?></td>
                    <td><?=$editInfo['flag0_count']?></td>
                </tr>
            </table>
        </div>

        <div class="editCheck">
            <button class="edit" type="button" name="update" value="cancel" onclick="location.href='foodsManagement.php'">キャンセル</button>
            <button class="delete" type="submit" name="update" value="update">変更</button>
        </div>
    </form>
</main>