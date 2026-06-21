// alerta de confirmação de acesso aos resultados da prova
function confirmResultAccess(checkbox) {
    const isChecked = checkbox.checked;

    // Texto diferente dependendo do estado do switch
    const message = isChecked ?
        'Isso irá liberar o acesso aos resultados da prova.' :
        'Isso irá remover o acesso aos resultados da prova.';

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
            document.getElementById('result-access-form').submit();
        } else {
            // Reverte o checkbox ao estado anterior se o usuário cancelar
            checkbox.checked = !isChecked;
        }
    });
}