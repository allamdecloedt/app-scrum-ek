

// Listen for file input change for educational qualifications



document.getElementById("school_image").addEventListener("change", function () {
  var fileName = this.value.split("\\").pop(); // Gets the file name
  if (fileName.length > 10) {
    fileName = fileName.substring(0, 10) + "... ." + getFileExtension(fileName); // Truncate the file name if it's too long
  }

  document.querySelector(".file-name-school-image").textContent = fileName;
});

//Check for Form selector to display the correct form

//-------------------------------------------------------------------------------

var schoolImageInput = document.querySelector("#school_image");
var schoolPreview = document.querySelector(".school-image-preview");
var schoolPreviewBtn = document.querySelector(".school-image-preview-btn");
var schoolModal = document.querySelector(".school-image-modal");
var schoolCloseBtn = document.querySelector(".close-school-image");

schoolImageInput.addEventListener("change", function () {
  var file = this.files[0];
  var fileUrl = URL.createObjectURL(file);
  console.log(fileUrl);
  schoolPreview.src = fileUrl;
  schoolPreviewBtn.classList.remove("disabled");
});

schoolPreviewBtn.addEventListener("click", function () {
  schoolModal.classList.toggle("display-none");
});

schoolCloseBtn.addEventListener("click", function () {
  schoolModal.classList.toggle("display-none");
});




var rpp = document.getElementById("repeat-password");
var p = document.getElementById("password");

p.addEventListener("input", function() {

    if (p.value !== rpp.value) {
        // Show error message
        document.getElementById("errorMessage").style.display = "block";
    }

    if (rpp.value === this.value || rpp.value === "") {
        // Hide error message
        document.getElementById("errorMessage").style.display = "none";
    }

});


rpp.addEventListener("input", function() {
    var password = document.getElementById("password").value;

    if (password !== this.value) {
        // Show error message
        document.getElementById("errorMessage").style.display = "block";
    }

    if (password === this.value || this.value === "") {
        // Hide error message
        document.getElementById("errorMessage").style.display = "none";
    }

});




document.getElementById("schoolform").addEventListener("submit", function(event) {
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("repeat-password").value;

    if (password !== confirmPassword) {
        // Prevent form submission
        event.preventDefault();
        // Show error message
        document.getElementById("errorMessage").style.display = "block";
    }
});