<main>
    <h1>編集</h1>
    <hr>
    <form action="recipeManagement.php" method="post">
        <section class="accordion">
            <input id="block-01" type="checkbox" class="toggle">
            <label class="Label" for="block-01">1｜トマト煮込み｜作成者</label>
            <div class="edit_containor">
                <div>
                    recipe名：<input type="text">
                    調理方法：<select name="how">
                        <option value="1">焼く</option>
                        <option value="2">煮る</option>
                        <option value="3">揚げる</option>
                    </select>
                    表示設定：
                    <input type="radio" id="show" name="show" value="show" checked />
                    <label for="show">表示</label>
                    <input type="radio" id="hide" name="show" value="hide" />
                    <label for="hide">非表示</label>
                </div>
                <div>
                    recipe画像をアップロード<input type="file" name="upfile">
                </div>
                <div>
                    メニューの説明：<textarea name="explanation"></textarea>
                </div>
                <div>
                    補足：<input type="text" name="supplement">
                </div>
                <div>
                    recipeリンク：<input type="text" name="recipeLink" id="">
                </div>
            </div>

            <hr>
            
            <input id="block-02" type="checkbox" class="toggle">
            <label class="Label" for="block-02">2｜ハンバーガー｜作成者</label>
            <div class="edit_containor">
                <div>
                    recipe名：<input type="text">
                    調理方法：<select name="how">
                        <option value="1">焼く</option>
                        <option value="2">煮る</option>
                        <option value="3">揚げる</option>
                    </select>
                    表示設定：
                    <input type="radio" id="show2" name="show2" value="show" checked />
                    <label for="show">表示</label>
                    <input type="radio" id="hide2" name="show2" value="hide" />
                    <label for="hide">非表示</label>
                </div>
                <div>
                    recipe画像をアップロード<input type="file" name="upfile">
                </div>
                <div>
                    メニューの説明：<textarea name="explanation"></textarea>
                </div>
                <div>
                    補足：<input type="text" name="supplement">
                </div>
                <div>
                    recipeリンク：<input type="text" name="recipeLink" id="">
                </div>
            </div>

            <hr>

            <input id="block-03" type="checkbox" class="toggle">
            <label class="Label" for="block-03">3｜もつ鍋｜作成者</label>
            <div class="edit_containor">
                <div>
                    recipe名：<input type="text">
                    調理方法：<select name="how">
                        <option value="1">焼く</option>
                        <option value="2">煮る</option>
                        <option value="3">揚げる</option>
                    </select>
                    表示設定：
                    <input type="radio" id="show3" name="show3" value="show" checked />
                    <label for="show">表示</label>
                    <input type="radio" id="hide3" name="show3" value="hide" />
                    <label for="hide">非表示</label>
                </div>
                <div>
                    recipe画像をアップロード<input type="file" name="upfile">
                </div>
                <div>
                    メニューの説明：<textarea name="explanation"></textarea>
                </div>
                <div>
                    補足：<input type="text" name="supplement">
                </div>
                <div>
                    recipeリンク：<input type="text" name="recipeLink" id="">
                </div>
            </div>
        </section>
        
        <hr>

        <div class="editCheck">
            <button type="button" onclick="location.href='recipeManagement.php'">キャンセル</button>
            <button type="submit">変更</button>
        </div>
    </form>
</main>