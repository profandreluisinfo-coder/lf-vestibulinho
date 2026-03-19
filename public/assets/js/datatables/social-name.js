$(document).ready(function () {

    const table = $('#subscribers').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        buttons: ["excel", "pdf", "print", "colvis"],
        responsive: true,
        autoWidth: true,
        lengthChange: true,
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 500],
            [10, 25, 50, 100, 500]
        ],
        ordering: true,
        info: true,
        dom: 'lBfrtip'
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /*
    ============================
    DEFERIR
    ============================
    */

    $('#subscribers').on('click', '.accept-social-name', function () {

        const btn = $(this);
        const url = btn.data('url');

        Swal.fire({
            title: 'Confirmar deferimento?',
            text: "O uso do nome social será deferido.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, deferir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (!result.isConfirmed) return;

            btn.prop('disabled', true);

            const originalHtml = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: url,
                type: 'PATCH',

                success: function (response) {

                    if (response.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: response.message
                        });
                        const row = table.row(btn.closest('tr'));
                        let data = row.data();

                        data[4] = response.data.status;
                        data[5] = response.data.actions;

                        row.data(data).draw(false);

                    } else {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: response.message
                        });

                        btn.prop('disabled', false).html(originalHtml);
                    }
                },

                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Não foi possível executar a operação.'
                    });

                    btn.prop('disabled', false).html(originalHtml);
                }
            });

        });

    });

    /*
    ============================
    INDEFERIR
    ============================
    */

    $('#subscribers').on('click', '.reject-social-name', function () {

        const btn = $(this);
        const url = btn.data('url');

        Swal.fire({
            title: 'Indeferir uso de nome social',
            input: 'textarea',
            inputLabel: 'Razão do indeferimento (opcional)',
            inputPlaceholder: 'Digite aqui a razão...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Indeferir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (!result.isConfirmed) return;

            const reason = result.value;

            btn.prop('disabled', true);

            const originalHtml = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: url,
                type: 'PATCH',
                data: { reason: reason },

                success: function (response) {

                    if (response.success) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Operação realizada',
                            text: response.message
                        });

                        const row = table.row(btn.closest('tr'));
                        let data = row.data();

                        data[4] = response.data.status;
                        data[5] = response.data.actions;

                        row.data(data).draw(false);

                    } else {

                        Swal.fire({
                            icon: 'warning',
                            title: 'Atenção',
                            text: response.message
                        });

                        btn.prop('disabled', false).html(originalHtml);
                    }
                },

                error: function () {

                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Não foi possível executar a operação.'
                    });

                    btn.prop('disabled', false).html(originalHtml);
                }
            });

        });

    });

});