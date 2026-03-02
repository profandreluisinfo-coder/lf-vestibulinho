document.addEventListener('DOMContentLoaded', function() {
    const socialNameOption1 = document.getElementById('radioYes');
    const socialNameOption2 = document.getElementById('radioNo');
    const socialNameDiv = document.getElementById('socialName');
    const authorizationDiv = document.getElementById('authorizationDiv');

    function toggleSocialNameField() {
        if (socialNameOption1.checked) {
            socialNameDiv.classList.remove('d-none');
            authorizationDiv.classList.remove('d-none');
        } else {
            socialNameDiv.classList.add('d-none');
            authorizationDiv.classList.add('d-none');
        }
    }

    socialNameOption1.addEventListener('change', toggleSocialNameField);
    socialNameOption2.addEventListener('change', toggleSocialNameField);

    setTimeout(toggleSocialNameField, 10);
});