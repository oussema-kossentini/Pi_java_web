const passwordInput = document.getElementById("password-input");
const passwordStrengthMeter = document.getElementById("password-strength-meter");

passwordInput.addEventListener("input", () => {
  const password = passwordInput.value;
  const score = calculatePasswordScore(password);

  passwordStrengthMeter.dataset.score = score;
  passwordStrengthMeter.className = getPasswordStrengthClassName(score);
});

function calculatePasswordScore(password) {
    let score = 0;
    if (password.length >= 8) {
      score++;
      if (password.match(/[a-z]/) && password.match(/[A-Z]/)) {
        score++;
      }
      if (password.match(/\d/)) {
        score++;
      }
      if (password.match(/[^\w\s]/)) {
        score++;
      }
    }
    return score;
  }
  
function getPasswordStrengthClassName(score) {
  if (score <= 2) {
    return "weak";
  } else if (score <= 6) {
    return "medium";
  } else {
    return "strong";
  }
}
