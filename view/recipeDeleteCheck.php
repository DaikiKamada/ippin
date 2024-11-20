<main>
    <h1>削除確認</h1>
    <hr>
    
    <form id="deleteForm" onsubmit="return checkDeleteInput('recipeManagement.php')" method="POST">
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

                <!-- サンプル行 -->
                <tr>
                    <td>トマト煮込み</td>
                    <td>トマト</td>
                    <td>コメント</td>
                    <td>補足</td>
                    <td>出典元</td>
                    <td>2024/10/25</td>
                    <td>表示</td>
                </tr>
                
                <tr>
                    <td>トマト煮込み</td>
                    <td>トマト</td>
                    <td>コメント</td>
                    <td>補足</td>
                    <td>出典元</td>
                    <td>2024/10/25</td>
                    <td>表示</td>
                </tr>

                <tr>
                    <td>トマト煮込み</td>
                    <td>トマト</td>
                    <td>コメント</td>
                    <td>補足</td>
                    <td>出典元</td>
                    <td>2024/10/25</td>
                    <td>表示</td>
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