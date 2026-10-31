// ✅ Função JavaScript para confirmação de exclusão (exemplo de uso abaixo)
function confirmDelete(callListId) {
    Swal.fire({
        title: 'Tem certeza?',
        html: `
            Essa ação não poderá ser desfeita.<br><br>
        `,
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