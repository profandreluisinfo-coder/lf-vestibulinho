// alerta de exclusão de arquivo de edital do processo seletivo
function confirmNoticeDelete(id, title) {
    Swal.fire({
        title: 'Tem certeza?',
        text: ` Você realmente deseja excluir este arquivo? Essa ação não poderá ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-notice-form-${id}`).submit();
        }
    });
}