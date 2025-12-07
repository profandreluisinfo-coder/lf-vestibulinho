// alerta de alteração de status de publicação de arquivo de prova anteiror do processo seletivo
function confirmFilePublish(id, fileName) {
    Swal.fire({
        title: 'Confirmar publicação',
        text: ` Você deseja alterar o status da publicação do arquivo: "${fileName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`archive-form-${id}`).submit();
        }
    });
}