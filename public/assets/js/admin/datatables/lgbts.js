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

});