document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('form[data-confirm-action]').forEach(function (form) {

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const action = form.dataset.confirmAction;
            const reason = form.querySelector('[name="reason"]');
            const reasonValue = reason ? reason.value.trim() : '';

            const isAccept = action === 'accept';

            Swal.fire({
                title: isAccept
                    ? 'Confirmar deferimento?'
                    : 'Confirmar indeferimento?',

                text: isAccept
                    ? 'O candidato será notificado por e-mail após o deferimento.'
                    : reasonValue
                        ? 'A ação será realizada com a razão informada.'
                        : 'A ação será realizada sem uma razão informada.',

                icon: isAccept ? 'question' : 'warning',

                showCancelButton: true,

                confirmButtonText: isAccept
                    ? 'Sim, deferir'
                    : 'Sim, indeferir',

                cancelButtonText: 'Cancelar',

                confirmButtonColor: isAccept
                    ? '#198754'
                    : '#dc3545',

                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

    });
});