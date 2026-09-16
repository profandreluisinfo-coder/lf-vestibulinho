document.querySelectorAll('.js-inscription-link').forEach((link) => {
    link.addEventListener('click', (event) => {
        event.preventDefault();

        // const loginUrl = @json(route('login'));
        // const registerUrl = @json(route('register'));
        const redirect = (hasAccount) => {
            window.location.href = hasAccount ? loginUrl : registerUrl;
        };

        if (window.Swal) {
            Swal.fire({
                title: 'Você já possui cadastro?',
                text: 'Acesse sua conta para continuar a inscrição ou crie um cadastro novo.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Já tenho cadastro',
                cancelButtonText: 'Ainda não',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    redirect(true);
                    return;
                }

                if (result.dismiss === Swal.DismissReason.cancel) {
                    redirect(false);
                    return;
                }

                // Fechou clicando fora, ESC, ou no X: cancela a operação, não faz nada.
            });

            return;
        }

        redirect(window.confirm('Você já possui cadastro?\n\nClique em OK para entrar ou em Cancelar para criar uma conta.'));
    });
});