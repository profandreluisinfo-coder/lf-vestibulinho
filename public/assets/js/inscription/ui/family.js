document.addEventListener('DOMContentLoaded', function () {
    const optionRadios = document.querySelectorAll('input[name="respLegalOption"]');
    const respLegalFields = document.querySelectorAll('.respLegal:not(#other_relationship)');
    const degreeSelect = document.getElementById('degree_id');
    const otherKinshipField = document.getElementById('other_relationship');

    const OTHER_DEGREE_ID = '8';

    /**
     * Remove o estado de erro de um campo (classe is-invalid + mensagens),
     * tanto as do jQuery Validate quanto as renderizadas pelo Blade.
     */
    function clearFieldFeedback(el) {
        el.classList.remove('is-invalid');
        const group = el.closest('.form-group');
        if (group) {
            group.querySelectorAll('.invalid-feedback, label.error').forEach(node => node.remove());
        }
    }

    /**
     * Mostra/esconde todos os campos do responsável legal.
     * Quando `show = true`, delega ao toggleOtherKinshipField() para
     * garantir que o campo "OUTRO" também respeite o valor atual do select.
     * Quando `show = false`, esconde o "OUTRO" e limpa TODO o bloco.
     */
    function toggleRespLegalFields(show) {
        respLegalFields.forEach(field => {
            field.classList.toggle('d-none', !show);
        });

        if (show) {
            toggleOtherKinshipField();
        } else {
            otherKinshipField.classList.add('d-none');
            clearRespLegalFields();
        }
    }

    /**
     * Limpa todos os campos do bloco de responsável legal
     * (valor + estado de erro). Usado quando o usuário escolhe "Não".
     */
    function clearRespLegalFields() {
        ['responsible', 'responsible_phone', 'degree_id', 'kinship'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.value = '';
                clearFieldFeedback(el);
            }
        });
    }

    /**
     * Mostra o campo "OUTRO" apenas quando degree_id = 8.
     * Ao esconder, limpa o input de kinship para não enviar lixo ao backend.
     */
    function toggleOtherKinshipField() {
        const show = degreeSelect?.value === OTHER_DEGREE_ID;

        otherKinshipField.classList.toggle('d-none', !show);

        if (!show) {
            clearKinshipInput();
        }
    }

    /**
     * Limpa o input de kinship (valor + erro).
     */
    function clearKinshipInput() {
        const kinshipInput = document.getElementById('kinship');
        if (kinshipInput) {
            kinshipInput.value = '';
            clearFieldFeedback(kinshipInput);
        }
    }

    // ---------------------------------------------------------------------
    // Inicialização
    // ---------------------------------------------------------------------
    const checkedOption = document.querySelector('input[name="respLegalOption"]:checked');
    toggleRespLegalFields(checkedOption?.value === '1');

    // ---------------------------------------------------------------------
    // Eventos
    // ---------------------------------------------------------------------
    optionRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            toggleRespLegalFields(this.value === '1');
        });
    });

    degreeSelect?.addEventListener('change', toggleOtherKinshipField);
});