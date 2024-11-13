document.querySelectorAll('.show-password').forEach(item => {
    item.addEventListener('click', function () {
        const passwordField = this.previousElementSibling;

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    });
});
