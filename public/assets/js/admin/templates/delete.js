// ✅ Função JavaScript para confirmação de exclusão do local de prova
function confirmTemplateDelete(id, templateName) {
    Swal.fire({
        title: 'Tem certeza?',
        text: `Você realmente deseja excluir o template "${templateName}"? Essa ação não poderá ser desfeita.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-template-form-${id}`).submit();
        }
    });
}