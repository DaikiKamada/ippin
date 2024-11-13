<main>
    <h1>削除確認</h1>
    <hr>
    <form id="deleteForm" onsubmit="return checkDeleteInput('foodsManagement.php')" method="POST">
        <div class="data_containor">
            <table class="data" border="1">
                <tr><th>ID</th><th>食材</th><th>カテゴリ</th><th>recipe件数</th><th>表示</th><th>非表示</th></tr>
                <tr><td>1</td><td>トマト</td><td>野菜</td><td>120</td><td>100</td><td>20</td></tr>
            </table>
        </div>

        <div class="deleteCheck">
            <h2>選択した食材を削除する場合は下記に「削除」と入力してください。</h2>
            <input type="text" id="deleteInput" placeholder="削除"><br>
            <button type="button" onclick="location.href='foodsManagement.php'">キャンセル</button>
            <button type="submit">削除</button>
        </div>
    </form>
</main>