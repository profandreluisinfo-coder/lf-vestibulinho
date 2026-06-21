$(document).ready(function() {
    // Configurar o Sortable.js no accordion
    const faqAccordion = document.getElementById('faqAccordion');
    
    if (faqAccordion) {
        new Sortable(faqAccordion, {
            animation: 150,
            handle: '.drag-handle', // Só arrasta pelo ícone
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            
            onEnd: function(evt) {
                // Coletar a nova ordem dos IDs
                let order = [];
                $('#faqAccordion .accordion-item').each(function() {
                    let faqId = $(this).data('faq-id');
                    order.push(faqId);
                });

                // Enviar para o servidor
                $.ajax({
                    url: '/faq/update-order',
                    method: 'PUT',
                    data: {
                        order: order,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Mostrar mensagem de sucesso (opcional)
                        console.log('Ordem atualizada!');
                    },
                    error: function(xhr) {
                        alert('Erro ao atualizar ordem. Tente novamente.');
                        console.error(xhr);
                    }
                });
            }
        });
    }
});