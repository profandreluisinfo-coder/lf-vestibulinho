// alerta de exclusão de arquivo de edital do processo seletivo
function confirmAnswerDelete(id, title) {
    Swal.fire({
        title: 'Tem certeza?',
        text: ` Esta ação não poderá ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-answer-form-${id}`).submit();
        }
    });
}