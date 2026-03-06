document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('degree');
    const otherDiv = document.getElementById('other_relationship');

    if (!select || !otherDiv) return;

    function toggleOtherField(value) {
        if (value == '8') {
            otherDiv.classList.remove('d-none');
        } else {
            otherDiv.classList.add('d-none');

            const kinshipInput = document.getElementById('kinship');
            if (kinshipInput) {
                kinshipInput.value = '';
            }
        }
    }

    // Ao carregar
    toggleOtherField(select.value);

    // Ao mudar
    select.addEventListener('change', function () {
        toggleOtherField(this.value);
    });
});