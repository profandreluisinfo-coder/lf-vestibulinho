// ✅ Função JavaScript para confirmação de exclusão (exemplo de uso abaixo)
function confirmDelete(callListId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Essa ação apagará a chamada e todos os candidatos vinculados.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${callListId}`).submit();
        }
    });
}