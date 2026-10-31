function confirmFinalize(callListId) {

    Swal.fire({
        title: 'Tem certeza?',
        html: `
            Essa ação não poderá ser desfeita.<br>
            Os candidatos receberão uma notificação por e-mail.
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, finalizar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {

            // console.log('ID enviado:', callListId);

            Swal.fire({
                title: 'Finalizando...',
                text: 'Por favor, aguarde',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            document.getElementById(`finalize-form-${callListId}`).submit();
        }
    });
}