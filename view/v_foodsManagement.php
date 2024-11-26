<main>
    <form action="foodsManagement.php" method="post" class="new">
        <div>
            食材名：<input type="text" name="foodName" id="foodName">
        </div>
        <div>
            食材分類：
            <select name="foodCatId">
                <!-- 食材カテゴリを表示 -->
                <?php
                    if (isset($vAry['foodCatM'])) {
                        $foodCatM = $vAry['foodCatM'];
                    } else {
                        $foodCatM = [];
                    }
                ?>
                <?php for($i = 0; $i < count($foodCatM); $i++) { ?>
                    <option value="<?=$foodCatM[$i]['foodCatId']?>"><?=$foodCatM[$i]['catName']?></option>
                <?php } ?>
            </select>
        </div>
        <button type="submit" id="submitBtn" class="disabled" name="insert" value="add" disabled>追加</button>
    </form>
    <hr>

    <!-- このフォーム -->
    <form id="fmTable" method="post">
        <div class="fM_containor">
            <table class="fM">
                <tr>
                    <th>ID</th>
                    <th>食材</th>
                    <th>カテゴリ</th>
                    <th>recipe件数</th>
                    <th>表示</th>
                    <th>非表示</th>
                    <th>操作</th>
                </tr>

                <!-- 食材一覧を表示 -->
                <?php
                    if (isset($vAry['foodsList'])) {
                        $foodsList = $vAry['foodsList'];
                    } else {
                        $foodsList = [];
                    }
                ?>
                <?php for($i = 0; $i < count($foodsList); $i++) { ?>
                    <tr>
                        <input type="hidden" id="fmTable<?= $foodsList[$i]['foodId'] ?>" name="choicedFoods[]" value="<?= $foodsList[$i]['foodId'] ?>">
                        <td><?=$foodsList[$i]['foodId']?></td>
                        <td><?=$foodsList[$i]['foodName']?></td>
                        <td><?=$foodsList[$i]['catName']?></td>
                        <td><?=$foodsList[$i]['recipe_count']?></td>
                        <td><?=$foodsList[$i]['flag1_count']?></td>
                        <td><?=$foodsList[$i]['flag0_count']?></td>

                        <td>
                            <!-- <button class="edit">編集</button> -->
                            <!-- <button class="delete">削除</button> -->
                            <!-- <button class="edit" onclick=submitClick() data-action="foodsEdit.php">編集</button> -->
                            <!-- <button class="delete" onclick=submitClick() data-action="foodsDeleteCheck.php">削除</button> -->
                            <a class="edit" href="foodsEdit.php?id=<?= $foodsList[$i]['foodId'] ?>" >編集</a>
                            <a class="f_delete" href="foodsDeleteCheck.php?id=<?= $foodsList[$i]['foodId'] ?>">削除</a>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        </div>
    </form>
</main>

<script>
    function submitClick(){
        let elm       = event.target;
        let actionUrl = elm.getAttribute("data-action");
        document.getElementById("fmTable").action = actionUrl;
    }
</script>