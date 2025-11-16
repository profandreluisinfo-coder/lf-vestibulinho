document.addEventListener('DOMContentLoaded', () => {
    const confirmBtn = document.getElementById('confirmSubmit');
    const cancelBtn = document.getElementById('cancelButton');
    const form = document.getElementById('finalize-inscription');
    const spinner = confirmBtn.querySelector('.spinner-border');
    const btnText = confirmBtn.querySelector('.btn-text');

    confirmBtn.addEventListener('click', () => {
        // Desabilita botões
        confirmBtn.disabled = true;
        cancelBtn.disabled = true;

        // Mostra spinner e esconde texto
        spinner.classList.remove('d-none');
        btnText.textContent = 'Enviando...';

        form.submit();
    });
});