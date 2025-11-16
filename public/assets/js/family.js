document.addEventListener('DOMContentLoaded', function () {
    const optionRadios = document.querySelectorAll('input[name="respLegalOption"]');
    const respLegalFields = document.querySelectorAll('.respLegal:not(#other_relationship)'); // Exclui o campo de parentesco
    const degreeSelect = document.getElementById('degree');
    const otherKinshipField = document.getElementById('other_relationship');

    function toggleRespLegalFields(show) {
        respLegalFields.forEach(field => {
            field.classList.toggle('d-none', !show);
        });
        
        // Só mostra o campo de parentesco se o grau for 8 E estiver mostrando os campos
        if (show) {
            toggleOtherKinshipField();
        } else {
            otherKinshipField.classList.add('d-none');
        }
    }

    function toggleOtherKinshipField() {
        const selectedDegree = degreeSelect.value;
        otherKinshipField.classList.toggle('d-none', selectedDegree !== '8');
    }

    // Inicialização
    const checkedOption = document.querySelector('input[name="respLegalOption"]:checked');
    const showRespLegal = checkedOption && checkedOption.value === '1';
    toggleRespLegalFields(showRespLegal);

    // Eventos
    optionRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleRespLegalFields(this.value === '1');
        });
    });
    
    degreeSelect.addEventListener('change', toggleOtherKinshipField);
});