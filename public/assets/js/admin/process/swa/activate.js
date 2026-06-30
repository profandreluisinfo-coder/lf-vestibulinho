// alerta de confirmação de acesso aos resultados da prova
function activateProcessSelective(checkbox) {
    const isChecked = checkbox.checked;

    // Texto diferente dependendo do estado do switch
    const message = isChecked ?
        'Esta ação irá habiltar o processo seletivo.' :
        'Esta ação irá desabilitar o processo seletivo.';

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
            document.getElementById('process-access-form').submit();
        } else {
            // Reverte o checkbox ao estado anterior se o usuário cancelar
            checkbox.checked = !isChecked;
        }
    });
}