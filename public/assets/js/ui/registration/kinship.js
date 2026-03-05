document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('degree');
    const otherDiv = document.getElementById('other_relationship');

    function toggleOtherField(value) {
        if (value == '8') { // '8' é o valor do parentesco "outro"
            otherDiv.classList.remove('d-none');
        } else {
            otherDiv.classList.add('d-none');
            document.getElementById('kinship').value = '';
        }
    }

    // Ativa ao carregar a página
    toggleOtherField(select.value);

    // Ativa ao mudar o valor do select
    select.addEventListener('change', function () {
        toggleOtherField(this.value);
    });
});