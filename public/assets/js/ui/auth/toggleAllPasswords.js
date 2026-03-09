document.getElementById('toggleAllPasswords').addEventListener('click', function () {

    let inputs = document.querySelectorAll('.password-field');
    let icon = this.querySelector('i');

    let showing = inputs[0].type === 'text';

    inputs.forEach(function (input) {
        input.type = showing ? 'password' : 'text';
    });

    if (showing) {
        icon.classList.replace('bi-eye-slash', 'bi-eye');
        this.innerHTML = '<i class="bi bi-eye"></i> Mostrar senhas';
    } else {
        icon.classList.replace('bi-eye', 'bi-eye-slash');
        this.innerHTML = '<i class="bi bi-eye-slash"></i> Ocultar senhas';
    }

});