function confirmDelete(url) {
    Swal.fire({
        title: 'Excluir post?',
        text: 'Esta ação é irreversível. Todos os anexos também serão removidos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: 'var(--color-danger)',
        cancelButtonColor: 'var(--color-muted)',
        confirmButtonText: '<i class="bi bi-trash3 me-1"></i> Excluir',
        cancelButtonText: 'Cancelar',
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.getElementById('delete-form');
            form.action = url;
            form.submit();
        }
    });
}