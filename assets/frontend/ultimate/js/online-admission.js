// Function to get the file extension

function getFileExtension(fileName) {
  // Split the fileName by period
  var parts = fileName.split(".");
  // Get the last part of the array which should be the extension
  var extension = parts[parts.length - 1];
  return extension.toLowerCase();
}

// Listen for file input change for student image

document
  .getElementById("student_image")
  .addEventListener("change", function () {
    var fileName = this.value.split("\\").pop(); // Gets the file name
    if (fileName.length > 10) {
      fileName =
        fileName.substring(0, 10) + "... ." + getFileExtension(fileName); // Truncate the file name if it's too long
    }

    document.querySelector(".file-name-photo").textContent = fileName;
  });

// Listen for file input change for educational qualifications

document.getElementById("pdf").addEventListener("change", function () {
  var fileName = this.value.split("\\").pop(); // Gets the file name
  if (fileName.length > 10) {
    fileName = fileName.substring(0, 10) + "... ." + getFileExtension(fileName); // Truncate the file name if it's too long
  }

  document.querySelector(".file-name-pdf").textContent = fileName;
});

document.getElementById("school_image").addEventListener("change", function () {
  var fileName = this.value.split("\\").pop(); // Gets the file name
  if (fileName.length > 10) {
    fileName = fileName.substring(0, 10) + "... ." + getFileExtension(fileName); // Truncate the file name if it's too long
  }

  document.querySelector(".file-name-school-image").textContent = fileName;
});

//Check for Form selector to display the correct form

var studentForm = document.getElementById("studentform");

var schoolForm = document.getElementById("schoolform");

var studentFormLine = document.getElementById("left-line");

var schoolFormLine = document.getElementById("right-line");

var studentFormSelector = document.getElementById("studentFormSelector");

var schoolFormSelector = document.getElementById("schoolFormSelector");

studentFormSelector.addEventListener("click", function () {
  document.getElementById("studentform").style.display = "block";
  document.getElementById("schoolform").style.display = "none";
  studentFormLine.classList.remove("underline-left");
  schoolFormLine.classList.add("underline-right");
  studentFormSelector.classList.add("active-form");
  schoolFormSelector.classList.remove("active-form");
  document
    .querySelector(".side-line-left")
    .setAttribute("data-selected", "true");
  document
    .querySelector(".side-line-right")
    .setAttribute("data-selected", "false");
});

schoolFormSelector.addEventListener("click", function () {
  document.getElementById("schoolform").style.display = "block";
  document.getElementById("studentform").style.display = "none";
  schoolFormLine.classList.remove("underline-right");
  studentFormLine.classList.add("underline-left");
  schoolFormSelector.classList.add("active-form");
  studentFormSelector.classList.remove("active-form");
  document
    .querySelector(".side-line-left")
    .setAttribute("data-selected", "false");
  document
    .querySelector(".side-line-right")
    .setAttribute("data-selected", "true");
});

var photoInput = document.querySelector("#student_image");
var photoPreview = document.querySelector(".photo-preview");
var photoPreviewBtn = document.querySelector(".photo-preview-btn");
var photoModal = document.querySelector(".photo-modal");
var photoCloseBtn = document.querySelector(".close-photo");

photoInput.addEventListener("change", function () {
  var file = this.files[0];
  var fileUrl = URL.createObjectURL(file);
  console.log(fileUrl);
  photoPreview.src = fileUrl;
  photoPreviewBtn.classList.remove("disabled");
});

photoPreviewBtn.addEventListener("click", function () {
  photoModal.classList.toggle("display-none");
});

photoCloseBtn.addEventListener("click", function () {
  photoModal.classList.toggle("display-none");
});

//-------------------------------------------------------------------------------

var pdfContainer = document.getElementById("pdf-container");
var pdfCloseBtn = document.querySelector(".close-pdf");
var pdfModal = document.querySelector(".pdf-modal");
var pdfPreviewBtn = document.querySelector(".pdf-preview-btn");
var pdfInput = document.querySelector("#pdf");

pdfInput.addEventListener("change", function () {
  const file = pdfInput.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      const pdf = document.createElement("object");
      pdf.setAttribute("data", e.target.result);
      pdf.setAttribute("type", "application/pdf");
      pdf.classList.add("preview");
      pdfContainer.appendChild(pdf);
      pdfPreviewBtn.classList.remove("disabled");
    };
    reader.readAsDataURL(file);
  }

  pdfPreviewBtn.addEventListener("click", function () {
    pdfModal.classList.toggle("display-none");
  });

  pdfCloseBtn.addEventListener("click", function () {
    pdfModal.classList.toggle("display-none");
  });
});



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
    var password = document.getElementById("password").value;

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