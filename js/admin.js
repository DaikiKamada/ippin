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