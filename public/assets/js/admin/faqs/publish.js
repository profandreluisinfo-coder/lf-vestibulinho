/**
 * Mostra uma alerta de confirmação para alterar o status da publicação de uma FAQ.
 * Se a opção for confirmada, o formulário de publicação será submetido.
 * 
 * @param {number} id - ID da FAQ a ser publicada.
 * @param {string} question - Pergunta da FAQ a ser publicada.
 */
function confirmFaqPublish(id, question) {
    Swal.fire({
        title: 'Confirmar publicação',
        text: `Você deseja alterar o status da publicação da pergunta: "${question}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`publish-faq-form-${id}`).submit();
        }
    });
}