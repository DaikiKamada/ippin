<main>
    <form action="#" method="post" class="new">
        <div>
            食材名：<input type="text">
        </div>
        <div>
            食材分類：
            <select>
                <option value="1">野菜</option>
                <option value="2">肉</option>
                <option value="3">その他</option>
            </select>
        </div>
        <button type="submit">追加</button>
    </form>
    <hr>
    <div class="fM_containor">
        <table class="fM">
            <tr><th>ID</th><th>食材</th><th>カテゴリ</th><th>recipe件数</th><th>有効</th><th>無効</th><th>操作</th></tr>
            <!-- サンプル行 -->
            <tr>
                <td>1</td>
                <td>トマト</td>
                <td>野菜</td>
                <td>120</td>
                <td>100</td>
                <td>20</td>
                <td>
                    <button class="edit" onclick="location.href='foodsEdit.php'">編集</button>
                    <button class="delete" onclick="location.href='foodsDeleteCheck.php?deleted=true'">削除</button>
                </td>
            </tr>
            <!-- 他の行も追加 -->
        </table>
    </div>
</main>