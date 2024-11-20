<main>
    <h1>recipe編集</h1>
    <hr>
    <form action="recipeManagement.php" method="post" class="recipeEdit">
        <section class="accordion">
            <input id="block-01" type="checkbox" class="toggle">
            <label class="Label" for="block-01">1｜トマト煮込み｜作成者</label>
            <div class="edit_containor">
                <div>
                    <label>recipe名：</label>
                    <input type="text" name="recipeName">
                </div>
                
                <div class="dropdown">
                    <button type="button" id="dropdownButton">食材を選択（3つまで）</button>
                    <div class="dropdown-content">
                        <label><input type="checkbox" value="みかん" onclick="limitCheckboxes(this)"> みかん</label>
                        <label><input type="checkbox" value="キャベツ" onclick="limitCheckboxes(this)"> キャベツ</label>
                        <label><input type="checkbox" value="タマゴ" onclick="limitCheckboxes(this)"> タマゴ</label>
                        <label><input type="checkbox" value="タマネギ" onclick="limitCheckboxes(this)"> タマネギ</label>
                        <label><input type="checkbox" value="レタス" onclick="limitCheckboxes(this)"> レタス</label>
                    </div>
                </div>

                <div>
                    <label>調理方法：</label>
                    <select name="howtoId">
                        <option value="1">焼く</option>
                        <option value="2">煮る</option>
                        <option value="3">揚げる</option>
                    </select>
                </div>

                <div class="full-width">
                    <label>コメント：</label>
                    <textarea name="comment"></textarea>
                </div>
                
                <div>
                    <label>補足：</label>
                    <input type="text" name="supplement">
                </div>
                
                <div>
                    <label>recipe画像をアップロード</label>
                    <input type="file" name="img">
                </div>
                
                <div>
                    <label>recipeリンク：</label>
                    <input type="text" name="url" >
                </div>

                <div>
                    <label>出典元：</label>
                    <input type="text" name="siteName">
                </div>
            
                <div>
                    <label>表示設定：</label>
                    <div>
                        <input type="radio" id="show" name="recipeFlag" value="show" checked/>
                        <label for="show">表示</label>
                        <input type="radio" id="hide" name="recipeFlag" value="hide" />
                        <label for="hide">非表示</label>
                    </div>
                </div>
            </div>

            <hr>
            
            <input id="block-02" type="checkbox" class="toggle">
            <label class="Label" for="block-02">2｜ハンバーガー｜作成者</label>
            <div class="edit_containor">
                <div>
                    <label>recipe名：</label>
                    <input type="text" name="recipeName">
                </div>
                
                <div class="dropdown">
                    <button type="button" id="dropdownButton">食材を選択（3つまで）</button>
                    <div class="dropdown-content">
                        <label><input type="checkbox" value="みかん" onclick="limitCheckboxes(this)"> みかん</label>
                        <label><input type="checkbox" value="キャベツ" onclick="limitCheckboxes(this)"> キャベツ</label>
                        <label><input type="checkbox" value="タマゴ" onclick="limitCheckboxes(this)"> タマゴ</label>
                        <label><input type="checkbox" value="タマネギ" onclick="limitCheckboxes(this)"> タマネギ</label>
                        <label><input type="checkbox" value="レタス" onclick="limitCheckboxes(this)"> レタス</label>
                    </div>
                </div>

                <div>
                    <label>調理方法：</label>
                    <select name="howtoId">
                        <option value="1">焼く</option>
                        <option value="2">煮る</option>
                        <option value="3">揚げる</option>
                    </select>
                </div>

                <div class="full-width">
                    <label>コメント：</label>
                    <textarea name="comment"></textarea>
                </div>
                
                <div>
                    <label>補足：</label>
                    <input type="text" name="supplement">
                </div>
                
                <div>
                    <label>recipe画像をアップロード</label>
                    <input type="file" name="img">
                </div>
                
                <div>
                    <label>recipeリンク：</label>
                    <input type="text" name="url" >
                </div>

                <div>
                    <label>出典元：</label>
                    <input type="text" name="siteName">
                </div>
            
                <div>
                    <label>表示設定：</label>
                    <div>
                        <input type="radio" id="show" name="recipeFlag" value="show" checked/>
                        <label for="show">表示</label>
                        <input type="radio" id="hide" name="recipeFlag" value="hide" />
                        <label for="hide">非表示</label>
                    </div>
                </div>
            </div>

            <hr>

            <input id="block-03" type="checkbox" class="toggle">
            <label class="Label" for="block-03">3｜もつ鍋｜作成者</label>
            <div class="edit_containor">
                <div>
                    <label>recipe名：</label>
                    <input type="text" name="recipeName">
                </div>
                
                <div class="dropdown">
                    <button type="button" id="dropdownButton">食材を選択（3つまで）</button>
                    <div class="dropdown-content">
                        <label><input type="checkbox" value="みかん" onclick="limitCheckboxes(this)"> みかん</label>
                        <label><input type="checkbox" value="キャベツ" onclick="limitCheckboxes(this)"> キャベツ</label>
                        <label><input type="checkbox" value="タマゴ" onclick="limitCheckboxes(this)"> タマゴ</label>
                        <label><input type="checkbox" value="タマネギ" onclick="limitCheckboxes(this)"> タマネギ</label>
                        <label><input type="checkbox" value="レタス" onclick="limitCheckboxes(this)"> レタス</label>
                    </div>
                </div>

                <div>
                    <label>調理方法：</label>
                    <select name="howtoId">
                        <option value="1">焼く</option>
                        <option value="2">煮る</option>
                        <option value="3">揚げる</option>
                    </select>
                </div>

                <div class="full-width">
                    <label>コメント：</label>
                    <textarea name="comment"></textarea>
                </div>
                
                <div>
                    <label>補足：</label>
                    <input type="text" name="supplement">
                </div>
                
                <div>
                    <label>recipe画像をアップロード</label>
                    <input type="file" name="img">
                </div>
                
                <div>
                    <label>recipeリンク：</label>
                    <input type="text" name="url" >
                </div>

                <div>
                    <label>出典元：</label>
                    <input type="text" name="siteName">
                </div>
            
                <div>
                    <label>表示設定：</label>
                    <div>
                        <input type="radio" id="show" name="recipeFlag" value="show" checked/>
                        <label for="show">表示</label>
                        <input type="radio" id="hide" name="recipeFlag" value="hide" />
                        <label for="hide">非表示</label>
                    </div>
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