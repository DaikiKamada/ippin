//////////////////// 全体 ////////////////////
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



//////////////////// main.php ////////////////////
// 食材選択数の制御
// DOMの読み込みが完了したら実行
document.addEventListener('DOMContentLoaded', function() {
  const selectionForm = document.getElementById('selectionForm'); // 対象のフォームのIDに合わせる
  const submitButton = document.querySelector('input[type="submit"]'); // 送信ボタンの要素

  // フォームが存在する場合のみ処理を実行
  if (selectionForm && submitButton) {
    function limitSelection(minCount, maxCount) {
      const checkboxes = selectionForm.querySelectorAll('input[type="checkbox"]');

      checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
          const selectedCount = selectionForm.querySelectorAll('input[type="checkbox"]:checked').length;

          if (selectedCount > maxCount) {
            alert(`最大${maxCount}つまでしか選択できません`);
            checkbox.checked = false; // 選択を超えた場合はチェックを外す
          } else if (selectedCount >= minCount) {
            submitButton.disabled = false; // 最低数を満たしたらボタンを有効に
          } else {
            submitButton.disabled = true; // 選択数が最低数未満ならボタンを無効に
          }
        });
      });
    }

    // 最初は送信ボタンを無効にする
    submitButton.disabled = true;

    // 最低1つ、最高3つの選択制限を設定
    limitSelection(1, 3);

    // フォーム送信時に最低1つのチェックがあるか確認
    submitButton.addEventListener('click', function(event) {
      const selectedCount = selectionForm.querySelectorAll('input[type="checkbox"]:checked').length;
      
      if (selectedCount < 1) {
        alert("最低1つは選択してください"); // 0個の場合のアラート
        event.preventDefault(); // フォーム送信を中止
      }
    });
  }
});



//////////////////// login.php ////////////////////
// ログインフォームのバリデーションと制御
document.addEventListener('DOMContentLoaded', function() {
  const mailAddressInput = document.getElementById('mailAddress');
  const passwordInput = document.getElementById('password');
  const submitButton = document.getElementById('submitBtn');
  const loginForm = document.getElementById('loginForm');

  // ログインフォームが存在する場合のみ、以下の処理を実行
  if (mailAddressInput && passwordInput && submitButton && loginForm) {

    // エラーメッセージを表示する関数
    function showErrorMessage(message) {
      alert(message);
    }

    // 入力が正しい場合にボタンを有効化
    function validateForm(event) {
      let errorMessages = [];

      // 空白チェック
      if (mailAddressInput.value.trim() === "") {
        errorMessages.push("メールアドレスを入力してください");
      }

      if (passwordInput.value.trim() === "") {
        errorMessages.push("ユーザーIDを入力してください");
      }
      
      // HTML5バリデーションチェック
      const mailValid = mailAddressInput.validity.valid;
      const passwordValid = passwordInput.validity.valid;

      // メールアドレスのバリデーション
      if (!mailValid && mailAddressInput.value.trim() !== "") {
        errorMessages.push("有効なメールアドレスを入力してください");
      }

      // パスワードのバリデーション
      if (!passwordValid && passwordInput.value.trim() !== "") {
        errorMessages.push("パスワードは半角英数・記号のみ使用できます");
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
    mailAddressInput.addEventListener('input', function() {
      submitButton.disabled = !passwordInput.value.trim() || !mailAddressInput.value.trim();  // 入力がない場合はボタンを無効化
    });

    passwordInput.addEventListener('input', function() {
      submitButton.disabled = !passwordInput.value.trim() || !mailAddressInput.value.trim();  // 入力がない場合はボタンを無効化
    });

    // フォーム送信時にバリデーションを実行
    loginForm.addEventListener('submit', validateForm);
  }
});



//////////////////// contact.php ////////////////////
// コンタクトフォームのバリデーションと制御
document.addEventListener("DOMContentLoaded", function() {
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const kindsInput = document.getElementById("kinds");
  const messageInput = document.getElementById("message");
  const termsCheckbox = document.getElementById("terms");
  const submitBtn = document.getElementById("submitBtn");
  const form = document.getElementById("contactForm");

  // エラーメッセージを表示する関数
  function showErrorMessage(message) {
    alert(message);
  }

  if (nameInput && emailInput && kindsInput && messageInput && termsCheckbox && submitBtn && form) {
    function checkInputs() {
      // 必須項目が入力されているかチェック
      const isFormValid = 
        nameInput.value.trim() !== "" &&
        emailInput.value.trim() !== "" &&
        kindsInput.value !== "" &&
        messageInput.value.trim() !== "" &&
        termsCheckbox.checked;
      
      submitBtn.disabled = !isFormValid;
    }

    // フォーム送信時のバリデーション
    function validateForm(event) {
      const errorMessages = [];

      // 空白チェック
      if (nameInput.value.trim() === "") {
        errorMessages.push("名前を入力してください");
      }
      
      if (emailInput.value.trim() === "") {
        errorMessages.push("有効なメールアドレスを入力してください");
      }

      if (kindsInput.value === "") {
        errorMessages.push("お問い合わせの種類を選択してください");
      }

      if (messageInput.value.trim() === "") {
        errorMessages.push("メッセージを入力してください");
      }

      if (!termsCheckbox.checked) {
        errorMessages.push("利用規約に同意する必要があります");
      }

      // エラーメッセージがある場合はアラートを表示して送信を中止
      if (errorMessages.length > 0) {
        showErrorMessage(errorMessages.join("\n"));
        event.preventDefault();
      }
    }

    // 入力やチェック状態が変更されるたびにチェック
    nameInput.addEventListener("input", checkInputs);
    emailInput.addEventListener("input", checkInputs);
    kindsInput.addEventListener("change", checkInputs);
    messageInput.addEventListener("input", checkInputs);
    termsCheckbox.addEventListener("change", checkInputs);

    // フォーム送信時にバリデーションを実行
    form.addEventListener("submit", validateForm);
  }
});



//////////////////// asobi ////////////////////
// 伝説のブタさん出現
document.addEventListener("DOMContentLoaded", function() {
  const butasan = document.querySelector(".butasan");
  const butasanImage = document.getElementById("butasanImage");

  if (butasan && butasanImage) {
      // 10%の確率で金色のブタさんに画像を差し替える
      if (Math.random() < 0.1) {
          butasanImage.src = "images/gold_pig.png"; // 金色のブタさん画像に差し替え
      }
  }
});
