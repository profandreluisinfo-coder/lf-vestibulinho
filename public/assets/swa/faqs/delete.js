// alerta de exclusão de pergunta de faq
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