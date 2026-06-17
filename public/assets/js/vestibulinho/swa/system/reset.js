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
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    Swal.fire('Redefinido!', data.message, 'success').then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire('Ops!', 'Ocorreu um problema ao redefinir o sistema.', 'error');
                });
        }
    });
}