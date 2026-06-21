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

    $('#subscribers').on('click', '.accept-report', function () {

        const btn = $(this);
        const url = btn.data('url');

        Swal.fire({
            title: 'Confirmar deferimento?',
            text: "O relatório/laudo será deferido.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, deferir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {

            if (!result.isConfirmed) return;

            const row = table.row(btn.closest('tr'));

            btn.prop('disabled', true);

            const originalHtml = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: url,
                type: 'PATCH',

                success: function (response) {

                    if (response.success) {

                        // coluna 5 = ações
                        table.cell(row.index(), 5).data('').draw(false);

                        // coluna 3 = status
                        table.cell(row.index(), 3).data(response.data.status).draw(false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: response.message
                        });

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

    $('#subscribers').on('click', '.reject-report', function () {

        const btn = $(this);
        const url = btn.data('url');

        Swal.fire({
            title: 'Indeferir relatório/laudo?',
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

            const row = table.row(btn.closest('tr'));

            btn.prop('disabled', true);

            const originalHtml = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: url,
                type: 'PATCH',
                data: { reason: reason },

                success: function (response) {

                    if (response.success) {

                        // coluna 5 = ações
                        table.cell(row.index(), 5).data('').draw(false);

                        // coluna 3 = status
                        table.cell(row.index(), 3).data(response.data.status).draw(false);

                        // coluna 4 = observações
                        table.cell(row.index(), 4).data(reason || '').draw(false);

                        Swal.fire({
                            icon: 'success',
                            title: 'Sucesso',
                            text: response.message
                        });

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