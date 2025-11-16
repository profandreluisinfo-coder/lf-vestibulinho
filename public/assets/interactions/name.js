document.addEventListener('DOMContentLoaded', function() {
    const socialNameOption1 = document.getElementById('radio1');
    const socialNameOption2 = document.getElementById('radio2');
    const socialNameDiv = document.getElementById('socialName');

    function toggleSocialNameField() {
        if (socialNameOption1.checked) {
            socialNameDiv.classList.remove('d-none');
        } else {
            socialNameDiv.classList.add('d-none');
        }
    }

    socialNameOption1.addEventListener('change', toggleSocialNameField);
    socialNameOption2.addEventListener('change', toggleSocialNameField);

    // Executa a verificação com pequeno atraso
    setTimeout(toggleSocialNameField, 10);
});