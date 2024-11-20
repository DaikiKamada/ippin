<main>
    <form action="#" method="post" class="new">
        <div>
            食材名：<input type="text" name="foodName" id="foodName">
        </div>
        <div>
            食材分類：
            <select name="foodCatId">
                <option value="1">肉</option>
                <option value="2">野菜</option>
                <option value="3">その他</option>
            </select>
        </div>
        <button type="submit" id="submitBtn" class="disabled" disabled>追加</button>
    </form>
    <hr>
    <form method="post" id="fmTable">
        <div class="fM_containor">
            <table class="fM">
                <tr><th>ID</th><th>食材</th><th>カテゴリ</th><th>recipe件数</th><th>表示</th><th>非表示</th><th>操作</th></tr>
                <!-- サンプル行 -->
                <!-- <tr>
                    <td>1</td>
                    <td>トマト</td>
                    <td>野菜</td>
                    <td>120</td>
                    <td>100</td>
                    <td>20</td>
                    <td>
                        <button class="edit">編集</button>
                        <button class="delete">削除</button>
                    </td>
                </tr> -->

                <!-- 食材一覧を表示 -->
                <?php
                if (isset($vAry['foodsList'])) {
                    $foodsList = $vAry['foodsList'];
                } else {
                    $foodsList = [];
                }
                ?>
                <?php
                for($i = 0; $i < count($foodsList); $i++) {
                    ?>
                    <tr>
                        <td><?=$foodsList[$i]['foodId']?></td>
                        <td><?=$foodsList[$i]['foodName']?></td>
                        <td><?=$foodsList[$i]['catName']?></td>

                        <!-- 調整予定 -->
                        <td>120</td>
                        <td>100</td>
                        <td>20</td>
                    
                        <td>
                            <button class="edit">編集</button>
                            <button class="delete">削除</button>
                        </td>

                    </tr> <?php
                }
                ?>

            </table>
        </div>
    </form>
</main>