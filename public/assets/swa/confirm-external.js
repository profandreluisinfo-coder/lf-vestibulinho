document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('a[href^="http"]').forEach(link => {
        const currentHost = window.location.hostname;

        if (!link.href.includes(currentHost)) {

            link.addEventListener('click', function (e) {
                e.preventDefault();

                const url = this.href;

                Swal.fire({
                    title: 'Redirecionamento externo',
                    text: 'Você será redirecionado para o site principal da escola. Deseja continuar?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Continuar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        window.open(url, '_blank');
                    }
                });
            });
        }
    });

});