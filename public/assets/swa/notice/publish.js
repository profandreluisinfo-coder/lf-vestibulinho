// alerta de alteração de status de publicação de arquivo de edital do processo seletivo
function confirmNoticePublish(id, title) {
    Swal.fire({
        title: 'Confirmar publicação',
        text: ` Alternar status do arquivo?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`publish-notice-form-${id}`).submit();
        }
    });
}