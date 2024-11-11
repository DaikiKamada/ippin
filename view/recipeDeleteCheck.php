<main>
    <h1>削除確認</h1>
    <hr>
    
    <form action="recipeManagement.php" method="POST" onsubmit="return checkDeleteInput()">
        <div class="d_recipe_containor">
            <table class="d_recipe">
                <tr>
                    <th>recipeID</th>
                    <th>recipe名</th>
                    <th>最終更新日</th>
                    <th>表示</th>
                </tr>

                <!-- サンプル行 -->
                <tr>
                    <td>1</td>
                    <td>トマト煮込み</td>
                    <td>2024/10/25</td>
                    <td>有効</td>
                </tr>
                
                <tr>
                    <td>2</td>
                    <td>ハンバーガー</td>
                    <td>2024/10/26</td>
                    <td>有効</td>
                </tr>
                
                <tr>
                    <td>3</td>
                    <td>もつ鍋</td>
                    <td>2024/10/27</td>
                    <td>有効</td>
                </tr>
                <!-- 他の行も追加 -->
            </table>
        </div>

        <div class="r_deleteCheck">
            <h2>選択したrecipeを削除する場合は下記に「削除」と入力してください。</h2>
            <input type="text" id="deleteInput" placeholder="削除"><br>
            <button type="button" onclick="location.href='recipeManagement.php'">キャンセル</button>
            <button type="submit">削除</button>
        </div>
    </form>
</main>