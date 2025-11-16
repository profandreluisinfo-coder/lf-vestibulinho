// ✅ Função JavaScript para confirmação de finalização da chamada
function confirmFinalize(callListId) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Essa ação não poderá ser desfeita. Os candidatos receberão uma notificação por e-mail.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, finalizar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra alerta de carregamento
            Swal.fire({
                title: 'Finalizando...',
                text: 'Por favor, aguarde',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Aqui você executa sua ação
            document.getElementById(`finalize-form-${callListId}`).submit();
        }
    });
}