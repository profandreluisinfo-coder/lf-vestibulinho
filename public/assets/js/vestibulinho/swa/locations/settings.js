// alerta de confirmação de acesso aos locais de prova e envio de e-mail
function confirmLocationAccess(checkbox) {
    const isChecked = checkbox.checked;

    // Texto diferente dependendo do estado do switch
    const message = isChecked ?
        'Isso irá liberar o acesso aos locais e notificar os candidatos por e-mail.' :
        'Isso irá remover o acesso aos locais.';

    Swal.fire({
        title: 'Confirmar ação',
        text: message + ' Deseja continuar?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra alerta de carregamento
            Swal.fire({
                title: 'Processando...',
                text: 'Por favor, aguarde alguns instantes.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Envia o formulário
            document.getElementById('location-access-form').submit();
        } else {
            // Reverte o checkbox ao estado anterior se o usuário cancelar
            checkbox.checked = !isChecked;
        }
    });
}