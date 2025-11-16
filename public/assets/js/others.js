document.addEventListener('DOMContentLoaded', function () {
    // Configurações para cada grupo de campos dependentes
    const dependentFields = [{
        selectId: 'health',
        fieldId: 'health_issue',
        showOnValue: '1' // Valor quando "Sim" é selecionado
    },
    {
        selectId: 'accessibility',
        fieldId: 'accessibility_description',
        showOnValue: '1'
    },
    {
        selectId: 'social_program',
        fieldId: 'nis',
        showOnValue: '1'
    }
    ];

    // Função para mostrar/ocultar campos dependentes
    function toggleDependentFields() {
        dependentFields.forEach(config => {
            const select = document.getElementById(config.selectId);
            const field = document.getElementById(config.fieldId);
            const parentDiv = field.closest('.form-group'); // Encontra o div pai que contém o campo

            if (select && field && parentDiv) {
                if (select.value === config.showOnValue) {
                    parentDiv.classList.remove('d-none');
                    field.disabled = false; // Habilita o campo para envio
                } else {
                    parentDiv.classList.add('d-none');
                    field.disabled = true; // Desabilita o campo para não ser enviado
                }
            }
        });
    }

    // Adiciona event listeners a todos os selects
    dependentFields.forEach(config => {
        const select = document.getElementById(config.selectId);
        if (select) {
            select.addEventListener('change', toggleDependentFields);
        }
    });

    // Executa a função ao carregar a página para configurar o estado inicial
    // toggleDependentFields();
    setTimeout(toggleDependentFields, 10);
});