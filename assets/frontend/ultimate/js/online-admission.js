

function getFileExtension(fileName) {
    // Split the fileName by period
    var parts = fileName.split('.');
    // Get the last part of the array which should be the extension
    var extension = parts[parts.length - 1];
    return extension.toLowerCase(); 
}


// Listen for file input change for student image

document.getElementById('student_image').addEventListener('change', function() {
    var fileName = this.value.split('\\').pop(); // Gets the file name
    if (fileName.length > 10) { 
        fileName = fileName.substring(0, 10) + '... .' + getFileExtension(fileName); // Truncate the file name if it's too long
    }

    document.querySelector('.file-name-photo').textContent = fileName;
});


// Listen for file input change for educational qualifications

document.getElementById('pdf').addEventListener('change', function() {
    var fileName = this.value.split('\\').pop(); // Gets the file name
    if (fileName.length > 10) { 
        fileName = fileName.substring(0, 10) + '... .' + getFileExtension(fileName); // Truncate the file name if it's too long
    }

    document.querySelector('.file-name-pdf').textContent = fileName;
});