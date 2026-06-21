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

            const row = table.row(btn.closest('tr'));
            const rowNode = $(row.node());

            btn.prop('disabled', true);

            const originalHtml = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: url,
                type: 'PATCH',

                success: function (response) {

                    if (response.success) {

                        const row = table.row(btn.closest('tr'));
                        const rowNode = $(row.node());

                        // remove o botão clicado (resolve o spinner travado)
                        btn.remove();

                        // limpa a célula de ações (remove outros botões)
                        rowNode.find('td').eq(6).html('');

                        // atualiza status
                        rowNode.find('td').eq(4).html(response.data.status);

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

            const row = table.row(btn.closest('tr'));
            const rowNode = $(row.node());

            btn.prop('disabled', true);

            const originalHtml = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span>');

            $.ajax({
                url: url,
                type: 'PATCH',
                data: { reason: reason },

                success: function (response) {

                    if (response.success) {

                        // limpa ações (remove botões e spinner)
                        rowNode.find('td').eq(6).html('');

                        // atualiza status
                        rowNode.find('td').eq(4).html(response.data.status);

                        // atualiza observação (coluna 5)
                        rowNode.find('td').eq(5).text(reason || '');

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