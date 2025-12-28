document
  .getElementById("imageUploadMain")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const imagePreview = document.getElementById("imagePreview");
        imagePreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

document
  .getElementById("imageUploadSub1")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const imagePreview = document.getElementById("imagePreviewSub1");
        imagePreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

document
  .getElementById("imageUploadSub2")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const imagePreview = document.getElementById("imagePreviewSub2");
        imagePreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

document
  .getElementById("imageUploadSub3")
  .addEventListener("change", function (event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        const imagePreview = document.getElementById("imagePreviewSub3");
        imagePreview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });