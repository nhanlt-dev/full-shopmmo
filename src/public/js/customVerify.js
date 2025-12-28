var inputs = document.querySelectorAll(".otp-card-inputs input");
var button = document.querySelector(".otp-card button");

function updateButtonState() {
  const allFilled = Array.from(inputs).every(
    (input) => input.value.length === 1
  );
  button.disabled = !allFilled;
}

function handleInput(e) {
  const currentElement = e.target;
  const nextElement = currentElement.nextElementSibling;

  const reg = /^[0-9]*$/;
  if (!reg.test(currentElement.value)) {
    currentElement.value = currentElement.value.replace(/\D/g, "");
  }
  if (currentElement.value && nextElement) {
    nextElement.focus();
  }

  updateButtonState();
}

function handleKeyDown(e) {
  const currentElement = e.target;
  const prevElement = currentElement.previousElementSibling;

  if (e.key === "Backspace" && currentElement.value === "") {
    if (prevElement) {
      prevElement.focus();
    }
  }
}

inputs.forEach((input) => {
  input.addEventListener("input", handleInput);
  input.addEventListener("keydown", handleKeyDown);

  // Sự kiện paste
  input.addEventListener("paste", function (e) {
    e.preventDefault();
    const pastedData = e.clipboardData.getData("text").trim();

    const numericData = pastedData.replace(/\D/g, "").split("").slice(0, 6);

    // Điền dữ liệu vào các ô input
    numericData.forEach((char, index) => {
      if (inputs[index]) {
        inputs[index].value = char;
      }
    });

    if (numericData.length === 6) {
      inputs[5].focus();
    }

    updateButtonState();
  });
});
