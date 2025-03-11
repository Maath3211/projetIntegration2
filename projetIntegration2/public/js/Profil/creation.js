document.getElementById('password').addEventListener('input', function () {
    if (this.value.length < 8) {
        this.setCustomValidity('Le mot de passe doit contenir au moins 8 caractères');
    } else {
        this.setCustomValidity('');
    }
});

document.getElementById('imageProfil').addEventListener('change', function () {
    var input = this;
    var fileNameDisplay = document.getElementById('filename');
    var noFileText = input.dataset.noFileText || "No file selected";

    // Check if any file is selected
    if (input.files && input.files.length > 0) {
        // Get the file name
        var fileName = input.files[0].name;

        // Display the file name in the div
        fileNameDisplay.textContent = fileName;
    } else {
        // If no file is selected, display the translated message
        fileNameDisplay.textContent = noFileText;
    }
});