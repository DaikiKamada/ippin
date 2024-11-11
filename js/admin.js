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
window.onload = resetFormIfDeleted;

function setAction(actionUrl) {
    document.getElementById('url').action = actionUrl;
}

document.getElementById('selectAll').addEventListener('change', function () {
    const checkboxes = document.querySelectorAll('input[name="choice"]');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

document.querySelectorAll('input[name="choice"]').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selectedCount = document.querySelectorAll('input[name="choice"]:checked').length;
    document.getElementById('selectedCount').textContent = selectedCount;
    const checkItems = document.getElementById('check_items');
    checkItems.innerHTML = '';
    document.querySelectorAll('input[name="choice"]:checked').forEach(checkbox => {
        const row = checkbox.closest('tr');
        const recipeName = row.cells[2].textContent;
        const recipeId = row.cells[1].textContent;
        const listItem = document.createElement('li');
        listItem.textContent = `ID: ${recipeId}, 名前: ${recipeName}`;
        checkItems.appendChild(listItem);
    });
}

// ページのスクロールに応じてボタンを表示
window.onscroll = function() { toggleScrollButton() };

function toggleScrollButton() {
    const scrollTopBtn = document.getElementById("scrollTopBtn");
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        scrollTopBtn.classList.add("show");
        scrollTopBtn.style.display = "block"; // スクロールしたら表示
    } else {
        scrollTopBtn.classList.remove("show");
        scrollTopBtn.style.display = "none"; // スクロール位置が上なら非表示
    }
}

// ページトップにスクロールする関数
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: "smooth" // スムーズなスクロール
    });
}
