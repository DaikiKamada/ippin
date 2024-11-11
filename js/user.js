// ハンバーガーメニュー
// メニューが展開された際のスクロール制御を改善
const backgroundFix = (bool) => {
  const scrollingElement = document.scrollingElement || document.documentElement;
  const scrollY = bool ? scrollingElement.scrollTop : parseInt(document.body.style.top || "0");
  
  // メニューが開いた時は、スクロール位置を固定せず、ページ全体を固定
  const fixedStyles = {
    position: "fixed",
    top: `-${scrollY}px`,
    left: "0",
    width: "100%",
    overflowY: "scroll"
  };
  
  Object.keys(fixedStyles).forEach((key) => {
    document.body.style[key] = bool ? fixedStyles[key] : "";
  });
  
  if (!bool) {
    window.scrollTo(0, scrollY * -1);  // 元の位置に戻る
  }
};

// 変数定義
const CLASS = "-active";
let isMenuOpen = false;
let isAccordionOpen = false;

const hamburger = document.getElementById("js-hamburger");
const focusTrap = document.getElementById("js-focus-trap");
const menu = document.querySelector(".js-nav-area");
const accordionTriggers = document.querySelectorAll(".js-sp-accordion-trigger");

// ハンバーガーメニューの開閉制御
hamburger.addEventListener("click", (e) => {
  e.currentTarget.classList.toggle(CLASS);
  menu.classList.toggle(CLASS);
  if (flg) {
    backgroundFix(false);
    hamburger.setAttribute("aria-expanded", "false");
    hamburger.focus();
    flg = false;
  } else {
    backgroundFix(true);
    hamburger.setAttribute("aria-expanded", "true");
    
    // メニュー展開時にトップへスクロールする
    window.scrollTo(0, 0);
    
    flg = true;
  }
});



// main.php
// 食材を3つ以上選択した場合、アラートが出て次に進めなくする
function limitSelection(maxCount) {
  const checkboxes = document.querySelectorAll('input[type="checkbox"]');
  
  checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
      const selectedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
      
      if (selectedCount > maxCount) {
        checkbox.checked = false; // チェックを外す
        alert(`最大${maxCount}つまでしか選択できません`);
        checkbox.blur(); // DOMの再計算
      }
    });
  });
}

// 最大3つまでの選択制限を設定
limitSelection(3);



// login.php
// ログインフォーム未入力時のsubmitボタン制御
document.addEventListener("DOMContentLoaded", function() {
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const submitBtn = document.getElementById("submitBtn");
  
  function checkInputs() {
    // 名前とメールアドレスの両方が入力されている場合のみボタンを有効化
    if (nameInput.value.trim() !== "" && emailInput.value.trim() !== "") {
          submitBtn.disabled = false;
      } else {
          submitBtn.disabled = true;
      }
  }

  // 入力が変更されるたびにチェックするイベントリスナーを追加
  nameInput.addEventListener("input", checkInputs);
  emailInput.addEventListener("input", checkInputs);
});



// contact.php
// コンタクトフォーム未入力時のsubmitボタン制御
document.addEventListener("DOMContentLoaded", function() {
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const kindsInput = document.getElementById("kinds");
  const messageInput = document.getElementById("message");
  const termsCheckbox = document.getElementById("terms");
  const submitBtn = document.getElementById("submitBtn");

  function checkInputs() {
      // 全ての必須フィールドが入力されている場合のみボタンを有効化
      if (
          nameInput.value.trim() !== "" &&
          emailInput.value.trim() !== "" &&
          kindsInput.value !== "" &&
          messageInput.value.trim() !== "" &&
          termsCheckbox.checked
      ) {
          submitBtn.disabled = false;
      } else {
          submitBtn.disabled = true;
      }
  }

  // 入力やチェック状態が変更されるたびにチェックするイベントリスナーを追加
  nameInput.addEventListener("input", checkInputs);
  emailInput.addEventListener("input", checkInputs);
  kindsInput.addEventListener("change", checkInputs);
  messageInput.addEventListener("input", checkInputs);
  termsCheckbox.addEventListener("change", checkInputs);
});

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
