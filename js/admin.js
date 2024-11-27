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

// 現在のページが特定のページであれば Back ボタンを非表示にする
if (window.location.pathname === '/ippin/manageTop.php') {
    document.getElementById('backButton').style.display = 'none';
}

//////////////////// DeleteCheck ////////////////////
document.addEventListener("DOMContentLoaded", function () {
    const deleteInput = document.getElementById("deleteInput");
    const deleteButton = document.querySelector("button.delete");

    // deleteInput が存在する場合のみ処理を実行
    if (deleteInput && deleteButton) {
        deleteInput.addEventListener("input", function () {
            if (deleteInput.value.trim() === "削除") {
                deleteButton.disabled = false;
            } else {
                deleteButton.disabled = true;
            }
        });
    }
});

// 'selectAll' 要素が存在する場合のみ addEventListener を設定
const selectAllCheckbox = document.getElementById('selectAll');
if (selectAllCheckbox) {
    selectAllCheckbox.addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="choicedRecipe[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });
}

// フォームのアクションURLを設定
function setAction(actionUrl) {
    document.getElementById('url').action = actionUrl;
}

//////////////////// recipeManagement.php　////////////////////
// "choice"チェックボックスの変更時にupdateSelectedCountを実行
document.querySelectorAll('input[id^="url"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

// 選択されたチェックボックス数を表示し、選択内容をリストに追加
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('input[name="choicedRecipe[]"]:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;

    const checkItems = document.getElementById('check_items');
    checkItems.innerHTML = '';
    document.querySelectorAll('input[name="choicedRecipe[]"]:checked').forEach(checkbox => {
        const row = checkbox.closest('tr');
        const recipeName = row.cells[1].textContent;
        const comment = row.cells[2].textContent;
        const memo = row.cells[3].textContent;
        const siteName = row.cells[4].textContent;
        const lastUpdate = row.cells[5].textContent;
        const recipeFlag = row.cells[6].textContent;

        const listItem = document.createElement('li');
        listItem.textContent = `recipe名: ${recipeName}, コメント: ${comment}, 補足: ${memo}, 出典元: ${siteName}, 最終更新日: ${lastUpdate}, 表示設定: ${recipeFlag}`;
        checkItems.appendChild(listItem);
    });
}

//////////////////// linkCheck.php　////////////////////
// "choice"チェックボックスの変更時にupdateSelectedCountを実行
document.querySelectorAll('input[id^="Url"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

// 選択されたチェックボックス数を表示し、選択内容をリストに追加
function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('input[name="choicedRecipe[]"]:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;

    const checkItems = document.getElementById('check_items');
    checkItems.innerHTML = '';
    document.querySelectorAll('input[name="choicedRecipe[]"]:checked').forEach(checkbox => {
        const row = checkbox.closest('tr');
        const recipeName = row.cells[1].textContent;
        const url = row.cells[2].textContent;
        const siteName = row.cells[3].textContent;
        const lastUpdate = row.cells[4].textContent;
        const recipeFlag = row.cells[5].textContent;

        const listItem = document.createElement('li');
        listItem.textContent = `recipe名: ${recipeName}, URL: ${url}, 出典元: ${siteName}, 最終更新日: ${lastUpdate}, 表示設定: ${recipeFlag}`;
        checkItems.appendChild(listItem);
    });
}

//////////////////// manageTop.php ////////////////////
function limitCheckboxes(checkbox) {
    // "manageTopPage" クラスを持つ要素内でのみ実行
    const mainElement = document.querySelector('.manageTopPage');
    if (!mainElement) {
        return;
    }

    // 対象のプルダウン内でチェックされたチェックボックスを取得
    const checkedCheckboxes = document.querySelectorAll(".dropdown-content input[type='checkbox']:checked");

    // 3つ以上選択された場合、チェックを解除して警告
    if (checkedCheckboxes.length > 3) {
        checkbox.checked = false;  // チェックを解除
        alert("3つまでしか選択できません。");
        return;
    }

    // 選択されたアイテムのfoodNameを取得（data-food-name属性）
    const selectedItems = Array.from(checkedCheckboxes).map(cb => cb.getAttribute('data-food-name'));

    // ドロップダウンボタンに選択内容を表示
    const dropdownButton = document.getElementById("dropdownButton");
    dropdownButton.innerText = selectedItems.length > 0 
        ? selectedItems.join(", ") 
        : "食材を選択（3つまで）";
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
    // 削除ボタンを取得
    const deleteButtons = document.querySelectorAll(".f_delete");

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

//////////////////// foodsEdit.php ////////////////////
document.addEventListener("DOMContentLoaded", function () {
    // フォームが存在するかを確認
    const foodForm = document.getElementById('foodForm');
    if (foodForm) {
        // フォーム送信時のイベントリスナーを設定
        foodForm.onsubmit = function (event) {
            const foodNameInput = document.querySelector('input[name="foodName"]');
            if (!foodNameInput.value.trim()) {
                alert('食材名を入力してください。');
                event.preventDefault();
            }
        };
    }
});

//////////////////// recipeManagement.php ////////////////////
document.addEventListener("DOMContentLoaded", function () {
    const recipeForm = document.querySelector(".newRecipe");

    if (recipeForm) {
        const recipeNameInput = document.querySelector("input[name='recipeName']");
        const urlInput = document.querySelector("input[name='url']");
        const siteNameInput = document.querySelector("input[name='siteName']");
        const imgInput = document.querySelector("input[name='upFile']");
        const recipeFlagRadios = document.querySelectorAll("input[name='recipeFlag']");
        const submitBtn = document.querySelector("button[type='submit']");

        // recipe名が255字以内かチェック
        recipeNameInput.addEventListener("input", function () {
            if (recipeNameInput.value.trim().length > 255) {
                alert("recipe名は255文字以内で入力してください。");
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

            // recipeリンクの詳細バリデーション（送信時にチェック）
            const urlValue = urlInput.value.trim();
            if (urlValue.length > 8190) {
                alert("recipeリンクは8190文字以内で入力してください。");
                isValid = false;
            } else if (!urlValue.startsWith("https://")) {
                alert("recipeリンクはhttps://で始めてください。");
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
    const recipeForm = document.querySelector(".newRecipe");
    const submitBtn = document.querySelector(".newRecipe button[type='submit']");

    if (recipeForm) {
        submitBtn.addEventListener("click", function (event) {
            let isValid = true;

            // 必須項目が空の場合エラー
            const recipeNameInput = document.querySelector("input[name='recipeName']");
            const urlInput = document.querySelector("input[name='url']");
            const siteNameInput = document.querySelector("input[name='siteName']");

            if (!recipeNameInput.value.trim() || !urlInput.value.trim() || !siteNameInput.value.trim()) {
                alert("コメント・メモ以外の項目を入力してください。");
                isValid = false;
            }

            // 送信を防ぐ
            if (!isValid) {
                event.preventDefault();
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

document.addEventListener("DOMContentLoaded", () => {
    // 特定のフォームを識別する（IDで指定）
    const form = document.getElementById("url");

    // フォームが存在しない場合はスクリプト終了（他のページに影響しない）
    if (!form) return;

    // 編集ボタンと削除ボタンを取得
    const editButton = form.querySelector('.edit');
    const deleteButton = form.querySelector('.delete');

    // クリックイベントを追加
    [editButton, deleteButton].forEach(button => {
        button.addEventListener("click", event => {
            // フォーム内の選択されているチェックボックスを取得
            const selectedCheckboxes = form.querySelectorAll('input[name="choicedRecipe[]"]:checked');

            // チェックが1つも選択されていない場合
            if (selectedCheckboxes.length === 0) {
                alert("少なくとも1つの項目を選択してください。");
                event.preventDefault(); // フォーム送信を防止
                return;
            }

            // 選択がある場合はactionを設定して送信
            const actionUrl = button.getAttribute("data-action");
            form.action = actionUrl;
            form.submit();
        });
    });
});

//////////////////// recipeEdit.php ////////////////////
document.addEventListener("DOMContentLoaded", function () {
    // レシピ編集フォームを取得
    const recipeForm = document.querySelector("form.recipeEdit");

    // フォームが存在する場合のみスクリプトを実行
    if (recipeForm) {
        // 各レシピのセクション（ブロック）を取得
        const recipeBlocks = recipeForm.querySelectorAll(".edit_containor");
        // 変更ボタンを取得
        const submitButton = recipeForm.querySelector(".editCheck button[type='submit']");

        // 各レシピブロックに対して処理を行う
        recipeBlocks.forEach((block, index) => {
            const label = block.previousElementSibling; // 各レシピブロックに対応するラベルを取得
            const recipeNameInput = block.querySelector(`input[name='${index}[recipeName]']`); // レシピ名入力フィールド
            const urlInput = block.querySelector(`input[name='${index}[url]']`); // レシピURL入力フィールド
            const siteNameInput = block.querySelector(`input[name='${index}[siteName]']`); // 出典元入力フィールド
            const howtoSelect = block.querySelector(`select[name='${index}[howtoId]']`); // 調理方法選択フィールド
            const recipeFlagRadios = block.querySelectorAll(`input[name='${index}[recipeFlag]']`); // レシピ表示設定ラジオボタン
            const ingredientCheckboxes = block.querySelectorAll(".dropdown-content input[type='checkbox']"); // 食材のチェックボックス群

            // 各ブロックのバリデーション関数
            function validateBlock() {
                let isValid = true;

                // レシピ名のバリデーション（空でない、255文字以内）
                if (recipeNameInput.value.trim() === "" || recipeNameInput.value.trim().length > 255) {
                    isValid = false;
                }

                // URLのバリデーション（空でない、最大8190文字、https://から始まる）
                const urlValue = urlInput.value.trim();
                if (urlValue === "" || urlValue.length > 8190 || !urlValue.startsWith("https://")) {
                    isValid = false;
                }

                // 出典元のバリデーション（空でない、255文字以内）
                if (siteNameInput.value.trim() === "" || siteNameInput.value.trim().length > 255) {
                    isValid = false;
                }

                // 調理方法の選択確認（選択されていること）
                if (howtoSelect.value.trim() === "") {
                    isValid = false;
                }

                // 表示設定の選択確認（表示または非表示のラジオボタンが選択されていること）
                let isRecipeFlagSelected = false;
                recipeFlagRadios.forEach((radio) => {
                    if (radio.checked) {
                        isRecipeFlagSelected = true;
                    }
                });
                if (!isRecipeFlagSelected) {
                    isValid = false;
                }

                // 食材の選択数のバリデーション（最大3つまで）
                let selectedIngredients = 0;
                let checkedIngredient = null;
                ingredientCheckboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        selectedIngredients++;
                        checkedIngredient = checkbox; // 最後に選ばれた食材を記録
                    }
                });

                // 3つ以上選ばれていた場合、4つ目を解除してエラーメッセージを表示
                if (selectedIngredients > 3) {
                    alert("食材は最大3つまでしか選択できません。");
                    if (checkedIngredient) {
                        checkedIngredient.checked = false;
                    }
                }

                // 食材が選ばれていない場合もエラー
                if (selectedIngredients === 0) {
                    isValid = false;
                }

                // バリデーション結果に基づいてラベルのスタイルを変更
                if (isValid) {
                    label.classList.remove("Error");
                    label.classList.add("success");
                } else {
                    label.classList.remove("success");
                    label.classList.add("Error");
                }
            }

            // 各入力項目にイベントリスナーを追加（入力時にバリデーションを実行）
            if (recipeNameInput) {
                recipeNameInput.addEventListener("input", validateBlock);
            }
            if (urlInput) {
                urlInput.addEventListener("input", validateBlock);
            }
            if (siteNameInput) {
                siteNameInput.addEventListener("input", validateBlock);
            }
            if (howtoSelect) {
                howtoSelect.addEventListener("input", validateBlock);
            }
            recipeFlagRadios.forEach((radio) => {
                radio.addEventListener("change", validateBlock);
            });
            ingredientCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener("change", validateBlock);
            });
        });

        // 変更ボタンがクリックされた時に全体のバリデーションを実行
        submitButton.addEventListener("click", function (event) {
            let formIsValid = true;

            // 各セクションの食材チェックボックスの選択数をカウント
            recipeBlocks.forEach((block) => {
                const ingredientCheckboxes = block.querySelectorAll(".dropdown-content input[type='checkbox']");
                let selectedIngredients = 0;

                ingredientCheckboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        selectedIngredients++;
                    }
                });

                // 食材が3つ以上選ばれている場合、フォーム送信を中止
                if (selectedIngredients > 3) {
                    formIsValid = false;
                }
            });

            // バリデーションエラーがあればフォーム送信を中止してアラートを表示
            if (!formIsValid) {
                event.preventDefault();
                alert("食材はセクションごとに最大3つまでしか選択できません。");
            }
        });
    }
});
