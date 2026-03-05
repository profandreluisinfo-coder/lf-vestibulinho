/**
 * Mostra uma alerta de confirmação para exclusão de uma FAQ.
 * 
 * @param {number} id - ID da FAQ a ser excluida
 * @param {string} question - Pergunta da FAQ a ser excluida
 */
function confirmFaqDelete(id, question) {
    Swal.fire({
        title: 'Tem certeza?',
        text: ` Você realmente deseja excluir a pergunta "${question}"? Essa ação não poderá ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-faq-form-${id}`).submit();
        }
    });
}