document.getElementById('password').addEventListener('input', function() {
    if (this.value.length < 8) {
        this.setCustomValidity('Le mot de passe doit contenir au moins 8 caractères');
    } else {
        this.setCustomValidity('');
    }
});