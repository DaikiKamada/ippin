<main>
    <h1>編集</h1>
    <hr>
    <form action="foodsManagement.php" method="POST">
        <div class="data_containor">
            <table class="data" border="1">
                <tr><th>ID</th><th>食材</th><th>カテゴリ</th><th>recipe件数</th><th>有効</th><th>無効</th></tr>
                <tr><td>1</td><td><input type="text" name="" id="" value="トマト" class="tdinput"></td><td><select class="tdinput">
                    <option value="1">野菜</option>
                    <option value="2">肉</option>
                    <option value="3">その他</option>
                </select></td><td>120</td><td>100</td><td>20</td></tr>
            </table>
        </div>

        <div class="editCheck">
            <button type="button" onclick="location.href='foodsManagement.php'">キャンセル</button>
            <button type="submit">変更</button>
        </div>
    </form>
</main>