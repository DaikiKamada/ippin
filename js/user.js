// 全体
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
let flg;
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
// ログインフォームのバリデーション
document.addEventListener('DOMContentLoaded', function() {
  const userIdInput = document.getElementById('userId');
  const mailAddressInput = document.getElementById('mailAddress');
  const submitButton = document.getElementById('submitBtn');
  const loginForm = document.getElementById('loginForm');

  // ログインフォームが存在する場合のみ、以下の処理を実行
  if (userIdInput && mailAddressInput && submitButton && loginForm) {

    // エラーメッセージを表示する関数
    function showErrorMessage(message) {
      alert(message);
    }

    // 入力が正しい場合にボタンを有効化
    function validateForm(event) {
      let errorMessages = [];

      const userIdValid = userIdInput.validity.valid;
      const mailValid = mailAddressInput.validity.valid;

      // ユーザーIDのバリデーション
      if (!userIdValid) {
        errorMessages.push("ユーザーIDは1〜11桁の数字で入力してください");
      }

      // メールアドレスのバリデーション
      if (!mailValid) {
        errorMessages.push("有効なメールアドレスを入力してください");
      }

      // エラーメッセージがあればアラートを表示し、フォーム送信を中止
      if (errorMessages.length > 0) {
        showErrorMessage(errorMessages.join("\n"));
        event.preventDefault();  // フォーム送信を中止
      } else {
        submitButton.disabled = false;
      }
    }

    // 入力内容が変更されるたびにバリデーションをチェック
    userIdInput.addEventListener('input', function() {
      submitButton.disabled = !userIdInput.value || !mailAddressInput.value;  // 入力がない場合はボタンを無効化
    });

    mailAddressInput.addEventListener('input', function() {
      submitButton.disabled = !userIdInput.value || !mailAddressInput.value;  // 入力がない場合はボタンを無効化
    });

    // フォーム送信時にバリデーションを実行
    loginForm.addEventListener('submit', validateForm);
  }
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

  if (nameInput && emailInput && kindsInput && messageInput && termsCheckbox && submitBtn) {
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
    kindsInput.addEventListener("change", checkInputs); // 修正
    messageInput.addEventListener("input", checkInputs);
    termsCheckbox.addEventListener("change", checkInputs);
  }
});
