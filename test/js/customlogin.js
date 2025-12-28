const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

registerBtn.addEventListener("click", () => {
  container.classList.add("active");
});

loginBtn.addEventListener("click", () => {
  container.classList.remove("active");
});

function toggleSignInPassword() {
  var passwordInput = document.getElementById("password");
  var toggleIcon = document.querySelector(".toggle-password");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIcon.classList.remove("fa-eye-slash");
    toggleIcon.classList.add("fa-eye");
  } else {
    passwordInput.type = "password";
    toggleIcon.classList.remove("fa-eye");
    toggleIcon.classList.add("fa-eye-slash");
  }
}

function toggleSignUpPassword() {
  var passwordInput = document.getElementById("password-signup");
  var toggleIconPassword = document.querySelector(".toggle-password-signup");

  if (passwordInput.type === "password") {
    passwordInput.type = "text";
    toggleIconPassword.classList.remove("fa-eye-slash");
    toggleIconPassword.classList.add("fa-eye");
  } else {
    passwordInput.type = "password";
    toggleIconPassword.classList.remove("fa-eye");
    toggleIconPassword.classList.add("fa-eye-slash");
  }
}

function toggleSignUpRePassword() {
  var repasswordInput = document.getElementById("repassword-signup");
  var toggleIconRepassword = document.querySelector(
    ".toggle-repassword-signup"
  );
  if (repasswordInput.type === "password") {
    repasswordInput.type = "text";
    toggleIconRepassword.classList.remove("fa-eye-slash");
    toggleIconRepassword.classList.add("fa-eye");
  } else {
    repasswordInput.type = "password";
    toggleIconRepassword.classList.remove("fa-eye");
    toggleIconRepassword.classList.add("fa-eye-slash");
  }
}
