$(document).ready(function () {
    var table = $('#subscribers').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('inscriptions.pcd.data') }}",
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        },
        columns: [
            { data: 'inscription_id', name: 'inscription_id' },
            { data: 'name', name: 'name' },
            { data: 'accessibility', name: 'accessibility' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                className: 'text-center no-export'
            }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Carregando...</span>'
        },
        responsive: true,
        autoWidth: false,
        lengthChange: true,
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, 500],
            [10, 25, 50, 100, 500]
        ],
        ordering: true,
        info: true,
        dom: 'lBfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel me-1 text-white"></i> Excel',
                className: 'btn btn-sm btn-success',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'pdf',
                text: '<i class="bi bi-file-earmark-pdf me-1 text-white"></i> PDF',
                className: 'btn btn-sm btn-danger',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1 text-white"></i> Imprimir',
                className: 'btn btn-sm btn-primary',
                exportOptions: {
                    columns: ':visible:not(.no-export)'
                }
            },
            {
                extend: 'colvis',
                text: '<i class="bi bi-eye me-1"></i> Colunas',
                className: 'btn btn-sm btn-secondary'
            }
        ]
    });

    table.buttons().container().appendTo('#subscribers_wrapper .col-md-6:eq(0)');
});