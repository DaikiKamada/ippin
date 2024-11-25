<main>
    <h1>削除確認</h1>
    <hr>
    <form action="foodsDeleteCheck.php" method="POST" id="deleteForm">
    <!-- <form id="deleteForm" onsubmit="return checkDeleteInput('foodsManagement.php')" method="POST"> -->
        <div class="data_containor">
            <table class="data" border="1">
                <tr><th>ID</th><th>食材</th><th>カテゴリ</th><th>recipe件数</th><th>表示</th><th>非表示</th></tr>
                <!-- 食材名を表示 -->
                <?php
                    if (isset($vAry['deleteInfo'])) {
                        $deleteInfo = $vAry['deleteInfo'];
                    } else {
                        $deleteInfo = [];
                    }
                ?>
                <tr>
                    <td><?=$deleteInfo['foodId']?></td>
                    <td><?=$deleteInfo['foodName']?></td>
                    <td><?=$deleteInfo['catName']?></td>
                    <td><?=$deleteInfo['recipe_count'] == 0? $deleteInfo['recipe_count']:'0'?></td>
                    <td><?=$deleteInfo['flag1_count'] == 0? $deleteInfo['flag1_count']:'0'?></td>
                    <td><?=$deleteInfo['flag0_count'] == 0? $deleteInfo['flag0_count']:'0'?></td>
                </tr>
            </table>
        </div>

        <div class="deleteCheck">
            <h2>選択した食材を削除する場合は下記に「削除」と入力してください。</h2>
            <input type="text" id="deleteInput" placeholder="削除"><br>
            <!-- <button class="edit" type="button" onclick="location.href='foodsManagement.php'">キャンセル</button>
            <button class="delete" type="submit">削除</button>  -->
            <button class="edit" type="submit" name="delete" value="cancel">キャンセル</button>
            <button class="delete" type="submit" name="delete" value="delete">削除</button>
        </div>
    </form>
</main>