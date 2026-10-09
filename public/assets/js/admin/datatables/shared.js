$(document).ready(function () {

    $('#subscribers').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
        },
        buttons: [
            "excel",
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':visible'
                },
                customize: function (doc) {
                    doc.content.forEach(function (item) {
                        if (item.table) {
                            item.table.widths = Array(item.table.body[0].length + 1).join('*').split('');

                            item.table.body.forEach(function (row) {
                                row.forEach(function (cell) {
                                    cell.alignment = 'center';
                                });
                            });
                        }
                    });
                }
            },
            "print",
            "colvis"
        ],
        responsive: true,
        autoWidth: true,
        lengthChange: true,
        pageLength: 10,
        lengthMenu: [
            [10, 25, 50, 100, 500],
            [10, 25, 50, 100, 500]
        ],
        ordering: true,
        info: true,
        dom: 'lBfrtip'
    });

});