document.addEventListener('DOMContentLoaded', function () {
    // ✅ Sucesso, erro, info, warning — vindos da sessão Laravel
    const alertTypes = ['success', 'error', 'warning', 'info'];

    alertTypes.forEach(type => {
        const message = document.body.dataset[`alert${type}`];
        if (message) {
            Swal.fire({
                icon: type,
                title: type.charAt(0).toUpperCase() + type.slice(1) + '!',
                text: message,
                confirmButtonText: 'Ok'
            });
        }
    });

    // ✅ Erros de validação (Laravel)
    const validationErrors = document.body.dataset.alertValidation;
    if (validationErrors) {
        Swal.fire({
            icon: 'error',
            title: 'Erros no formulário',
            html: validationErrors,
            confirmButtonText: 'Corrigir'
        });
    }
});

// ✅ Confirmação de exclusão
function confirmDelete(callListId) {
    Swal.fire({
        title: 'Tem certeza que deseja excluir?',
        text: 'Essa ação apagará a chamada e todos os candidatos vinculados.',
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

// ✅ Confirmação de finalização
function confirmFinalize(callListId) {
    Swal.fire({
        title: 'Finalizar Chamada?',
        text: 'Ao finalizar, os dados serão encerrados e não poderão ser alterados.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, finalizar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`finalize-form-${callListId}`).submit();
        }
    });
}