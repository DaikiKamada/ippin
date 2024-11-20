// 画面トップへのスクロール
// ページのスクロールに応じてボタンを表示
window.addEventListener("scroll", toggleScrollButton);

function toggleScrollButton() {
  const scrollTopBtn = document.getElementById("scrollTopBtn");
  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    scrollTopBtn.classList.add("show");
  } else {
    scrollTopBtn.classList.remove("show");
  }
}

// ページトップにスクロールする関数
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: "smooth" // スムーズなスクロール
  });
}

function checkDeleteInput(destination) {
    // 入力された値を取得
    const input = document.getElementById("deleteInput").value;

    // "削除" と一致しない場合、アラートを表示
    if (input !== "削除") {
        alert("「削除」と入力してください。");
        return false; // フォーム送信をキャンセル
    }

    // "削除"と入力された場合に、指定されたURLにリダイレクト
    window.location.href = destination + '?deleted=true';
    return false; // 通常のフォーム送信はキャンセル
}

// ページがロードされたときに実行される関数
function resetFormIfDeleted() {
    const urlParams = new URLSearchParams(window.location.search);

    // "deleted=true" のクエリパラメータがあるか確認
    if (urlParams.get('deleted') === 'true') {
        alert('削除しました');  // アラートを表示

        // アラート後にURLからクエリパラメータを削除
        window.history.replaceState({}, document.title, window.location.pathname);
    }
}

// ページロード時にリセット処理を実行
window.onload = function() {
    resetFormIfDeleted();

    // 'selectAll' 要素が存在する場合のみ addEventListener を設定
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="choice"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
};

// フォームのアクションURLを設定
function setAction(actionUrl) {
    document.getElementById('url').action = actionUrl;
}

// "choice"チェックボックスの変更時にupdateSelectedCountを実行
document.querySelectorAll('input[name="choice"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

// 選択されたチェックボックス数を表示し、選択内容をリストに追加
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('input[name="choice"]:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;

    const checkItems = document.getElementById('check_items');
    checkItems.innerHTML = '';
    document.querySelectorAll('input[name="choice"]:checked').forEach(checkbox => {
        const row = checkbox.closest('tr');
        const recipeName = row.cells[1].textContent;
        const foodValues = row.cells[2].textContent;
        const comment = row.cells[3].textContent;
        const supplement = row.cells[4].textContent;
        const siteName = row.cells[5].textContent;
        const lastUpdate = row.cells[6].textContent;
        const recipeFlag = row.cells[7].textContent;

        const listItem = document.createElement('li');
        listItem.textContent = `recipe名: ${recipeName}, 食材: ${foodValues}, コメント: ${comment}, 補足: ${supplement}, 出典元: ${siteName}, 最終更新日: ${lastUpdate}, 表示設定: ${recipeFlag}`;
        checkItems.appendChild(listItem);
    });
}



// 現在のページが特定のページであれば Back ボタンを非表示にする
if (window.location.pathname === '/ippin/manageTop.php') {
    document.getElementById('backButton').style.display = 'none';
}

function limitCheckboxes(checkbox) {
    // チェックされたチェックボックスを取得
    const checkedCheckboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]:checked');
    
    // 3つ以上選択された場合、チェックを解除し警告
    if (checkedCheckboxes.length > 3) {
        checkbox.checked = false;
        alert("3つまでしか選択できません。");
        return;
    }

    // 選択されたアイテムの名前を取得して表示
    const selectedItems = Array.from(checkedCheckboxes).map(cb => cb.value);
    document.getElementById("dropdownButton").innerText = selectedItems.length > 0 
        ? selectedItems.join(", ") 
        : "材料を選択（3つまで）";
}

document.addEventListener("DOMContentLoaded", function() {
    // 該当するフォームが存在する場合のみ処理を実行
    const form = document.querySelector(".mTform");
    
    // フォームが存在すれば処理を続ける
    if (form) {
        const searchButton = form.querySelector("button[type='submit']");

        searchButton.addEventListener("click", function(event) {
            // チェックボックスで選択されている項目を取得
            const checkedCheckboxes = document.querySelectorAll('.dropdown-content input[type="checkbox"]:checked');

            // 1つも選択されていない場合、アラートを表示し、送信を防止
            if (checkedCheckboxes.length === 0) {
                alert("少なくとも1つの食材を選択してください。");
                event.preventDefault();  // フォーム送信をキャンセル
            }
        });
    }
});



document.addEventListener("DOMContentLoaded", function () {
    // 削除ボタンを取得
    const deleteButtons = document.querySelectorAll(".delete");

    // 各削除ボタンにクリックイベントを追加
    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            // 親行（tr）を取得
            const row = button.closest("tr");

            // rowが存在するか確認
            if (!row) {
                console.error("親行（tr）が見つかりません。");
                return;
            }

            // 「表示」列のセルを取得（5番目のセル）
            const displayCountCell = row.cells[4];
            if (displayCountCell) {
                // セルの値を数値に変換
                const displayCount = parseInt(displayCountCell.textContent.trim(), 10);

                // displayCountが1以上の場合にアラートを表示
                if (!isNaN(displayCount) && displayCount >= 1) {
                    event.preventDefault();
                    alert("この項目は表示件数が1件以上のため削除できません。");
                    return;
                }
            } else {
                console.error("「表示」セルが見つかりません。");
            }

            // 表示件数が1未満の場合はフォーム送信を実行
            const form = document.getElementById('fmTable');
            if (form) {
                form.action = 'foodsDeleteCheck.php';
                form.submit();
            } else {
                console.error("フォームが見つかりません。");
            }
        });
    });

    // 編集ボタンを取得
    const editButtons = document.querySelectorAll(".edit");

    // 各編集ボタンにクリックイベントを追加
    editButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            // 親行（tr）を取得
            const row = button.closest("tr");

            // 編集ボタン押下後、表示件数に関係なく遷移
            const form = document.getElementById('fmTable');
            if (form) {
                form.action = 'foodsEdit.php';  // 編集ページに遷移
                form.submit();
            } else {
                console.error("フォームが見つかりません。");
            }
        });
    });
});



//////////////////// foodsManagement.php ////////////////////
// 食材Insertフォームのバリデーション制御 
document.addEventListener("DOMContentLoaded", function () {
    const foodNameInput = document.getElementById("foodName");
    const submitBtn = document.getElementById("submitBtn");

    if (foodNameInput && submitBtn) {
        // 入力が変更された際にクラスと属性を切り替え
        foodNameInput.addEventListener("input", function () {
            const foodName = foodNameInput.value.trim();

            // 65文字以上の場合にアラートを表示
            if (foodName.length >= 65) {
                alert("64文字以内でお願いします。");
            }

            if (foodName === "") {
                submitBtn.classList.add("disabled");
                submitBtn.setAttribute("disabled", true);
            } else {
                submitBtn.classList.remove("disabled");
                submitBtn.removeAttribute("disabled");
            }
        });

        // ボタンがクリックされたとき
        submitBtn.addEventListener("click", function(event) {            
            // ページ遷移時にクエリパラメータを追加して次のページへ遷移
            window.location.href = "foodsManagement.php?completed=true";
        });
    }
});

// 食材Insertフォームのsubmitボタン・アラート制御
document.addEventListener("DOMContentLoaded", function () {
    // URLのクエリパラメータを取得
    const urlParams = new URLSearchParams(window.location.search);
    
    // ?completed=true がある場合
    if (urlParams.has("completed") && urlParams.get("completed") === "true") {
        alert("登録完了しました");

        // クエリパラメータから 'completed' を削除
        history.replaceState(null, '', window.location.pathname);
    }
});



document.addEventListener("DOMContentLoaded", function () {
    const recipeForm = document.querySelector(".newRecipe");

    if (recipeForm) {
        const recipeNameInput = document.querySelector("input[name='recipeName']");
        const urlInput = document.querySelector("input[name='url']");
        const siteNameInput = document.querySelector("input[name='siteName']");
        const imgInput = document.querySelector("input[name='img']");
        const howtoSelect = document.querySelector("select[name='howtoId']");
        const recipeFlagRadios = document.querySelectorAll("input[name='recipeFlag']");
        const submitBtn = document.querySelector("button[type='submit']");

        // recipe名が255字以内かチェック
        recipeNameInput.addEventListener("input", function () {
            if (recipeNameInput.value.trim().length > 255) {
                alert("recipe名は255文字以内で入力してください。");
            }
        });

        // recipeリンクのバリデーション (8190文字以内かつ https:// で始まるかチェック)
        urlInput.addEventListener("input", function () {
            const value = urlInput.value.trim();
            if (value.length > 8190) {
                alert("recipeリンクは8190文字以内で入力してください。");
            } else if (!value.startsWith("https://")) {
                alert("recipeリンクはhttps://で始めてください。");
            }
        });

        // 出典元が128字以内かチェック
        siteNameInput.addEventListener("input", function () {
            if (siteNameInput.value.trim().length > 128) {
                alert("出典元は128文字以内で入力してください。");
            }
        });

        // フォーム送信時のバリデーション
        submitBtn.addEventListener("click", function (event) {
            let isValid = true;

            // 必須項目が空の場合エラー
            if (recipeNameInput.value.trim() === "" || urlInput.value.trim() === "" || siteNameInput.value.trim() === "") {
                alert("すべての項目を入力してください。");
                isValid = false;
            }

            // 調理方法の選択確認
            if (howtoSelect.value === "") {
                alert("調理方法を選択してください。");
                isValid = false;
            }

            // 表示設定の選択確認
            if (![...recipeFlagRadios].some(radio => radio.checked)) {
                alert("表示設定を選択してください。");
                isValid = false;
            }

            // 画像が選択されているか確認
            if (!imgInput.files.length) {
                alert("recipe画像をアップロードしてください。");
                isValid = false;
            }

            // recipeリンクの詳細バリデーション
            const urlValue = urlInput.value.trim();
            if (urlValue.length > 8190 || !urlValue.startsWith("https://")) {
                alert("recipeリンクはhttps://で始まり、8190文字以内で入力してください。");
                isValid = false;
            }

            // バリデーションに失敗した場合は送信を防止
            if (!isValid) {
                event.preventDefault();
            } else {
                // 成功時
                sessionStorage.setItem("completed", "true");
                window.location.href = "recipeManagement.php";
            }
        });
    }
});

document.addEventListener("DOMContentLoaded", function () {
    if (sessionStorage.getItem("completed") === "true") {
        alert("登録完了しました");
        sessionStorage.removeItem("completed");
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const recipeBlocks = document.querySelectorAll(".edit_containor"); // 各ブロックを取得

    // 各ブロックのバリデーション
    recipeBlocks.forEach((block) => {
        const label = block.previousElementSibling; // 対応するラベルを取得
        const recipeNameInput = block.querySelector("input[name='recipeName']");
        const urlInput = block.querySelector("input[name='url']");
        const siteNameInput = block.querySelector("input[name='siteName']");
        const imgInput = block.querySelector("input[name='img']");
        const howtoSelect = block.querySelector("select[name='howtoId']");
        const recipeFlagRadios = block.querySelectorAll("input[name='recipeFlag']");
        const ingredientCheckboxes = block.querySelectorAll(".dropdown-content input[type='checkbox']");

        // ブロックごとのバリデーション関数
        function validateBlock() {
            let isValid = true;

            // 各入力項目を確認
            if (recipeNameInput.value.trim() === "" || recipeNameInput.value.trim().length > 255) {
                isValid = false;
            }

            if (urlInput.value.trim() === "" || urlInput.value.trim().length > 255) {
                isValid = false;
            }

            if (siteNameInput.value.trim() === "" || siteNameInput.value.trim().length > 255) {
                isValid = false;
            }

            if (imgInput.value.trim() === "" || imgInput.value.trim().length > 255) {
                isValid = false;
            }

            if (howtoSelect.value.trim() === "") {
                isValid = false;
            }

            let isRecipeFlagSelected = false;
            recipeFlagRadios.forEach((radio) => {
                if (radio.checked) {
                    isRecipeFlagSelected = true;
                }
            });
            if (!isRecipeFlagSelected) {
                isValid = false;
            }

            let isIngredientSelected = false;
            ingredientCheckboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    isIngredientSelected = true;
                }
            });
            if (!isIngredientSelected) {
                isValid = false;
            }

            // ラベルの色を変更
            if (isValid) {
                label.classList.remove("Error");
                label.classList.add("success");
            } else {
                label.classList.remove("success");
                label.classList.add("Error");
            }
        }

        // 各入力項目にイベントリスナーを追加
        recipeNameInput.addEventListener("input", validateBlock);
        urlInput.addEventListener("input", validateBlock);
        siteNameInput.addEventListener("input", validateBlock);
        imgInput.addEventListener("input", validateBlock);
        howtoSelect.addEventListener("input", validateBlock);
        recipeFlagRadios.forEach((radio) => {
            radio.addEventListener("change", validateBlock);
        });
        ingredientCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener("change", validateBlock);
        });
    });
});
