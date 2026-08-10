// alerta de redefinição de dados do sistema
function resetSystem() {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Essa ação não poderá ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, redefinir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {

            // Mostra loading
            Swal.fire({
                title: 'Processando...',
                text: 'Estamos redefinindo os dados do sistema.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(resetUrl, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(async response => {
                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Não foi possível redefinir o sistema.');
                    }

                    return data;
                })
                .then(data => {
                    Swal.fire('Redefinido!', data.message, 'success').then(() => {
                        window.location.href = '/admin/login';
                    });
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Ops!', error.message || 'Ocorreu um problema ao redefinir o sistema.', 'error');
                });
        }
    });
}
