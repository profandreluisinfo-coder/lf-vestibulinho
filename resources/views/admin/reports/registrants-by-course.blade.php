@extends('layouts.admin')

@section('page-title', 'Candidatos Inscritos por Curso')

@push('datatable-styles')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Lado esquerdo: ícone + texto -->
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-people"></i>
                <h6 class="mb-0 text-muted fw-normal">Candidatos Inscritos por Curso</h6>
            </div>

            <!-- Lado direito: agrupa os dois botões -->
            <div class="d-flex gap-2">
                {{-- <a id="pdfButton" href="#" class="btn btn-sm btn-danger">
                    <i class="bi bi-filetype-pdf"></i>
                    <span>Gerar PDF</span>
                </a> --}}
                <a id="graphButton" href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                    data-bs-target="#modalCursos" title="Visualizar gráfico">
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Gráfico</span>
                </a>
            </div>
        </div>

        <table id="subscribers" class="table table-striped table-hover caption-top align-middle">
            <caption>Vestibulinho LF {{ $process?->year }} - Candidatos Inscritos por Curso</caption>
            <thead class="table-success text-center">
                <tr>
                    <th><i class="bi bi-credit-card me-1"></i>Curso</th>
                    <th><i class="bi bi-hash me-1"></i>Inscrição</th>
                    <th><i class="bi bi-person me-1"></i>Candidato</th>
                    <th><i class="bi bi-credit-card me-1"></i>CPF</th>
                    {{-- <th><i class="bi bi-credit-card me-1"></i>Sexo</th> --}}
                </tr>
            </thead>
            <tbody class="table-group-divider">
                @foreach ($inscriptions as $inscription)
                    <tr>
                        <td>{{ $inscription->course->name }}</td>
                        <td class="text-center">{{ $inscription->id }}</td>
                        <td>{{ $inscription->user->name }}</td>
                        <td class="text-center">{{ $inscription->user->cpf }}</td>
                        {{-- <td class="text-center">{{ $inscription->user->gender }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

    {{-- Modal: Cursos --}}
    <div class="modal fade" id="modalCursos">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-bar-chart-fill"></i> Inscritos por Curso</h5>
                    <div class="ms-auto d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary"
                            onclick="imprimirGrafico('chartCursos', 'Inscritos por Curso')" style="color: white;">
                            <i class="bi bi-printer"></i>
                            <span>Imprimir</span>
                        </button>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> --}}
                    </div>
                </div>
                <div class="modal-body">
                    <canvas id="chartCursos" data-labels='@json($cursos->pluck('curso'))'
                        data-values='@json($cursos->pluck('total'))'></canvas>

                    <div class="d-flex flex-wrap gap-2 justify-content-center mt-5 pb-3 px-3">
                        <a href="{{ route('admin.export.courses.pdf') }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf"></i> Exportar Dados PDF
                        </a>
                        <a href="{{ route('admin.export.courses.excel') }}" class="btn btn-outline-success btn-sm">
                            <i class="bi bi-file-earmark-excel"></i> Exportar Dados Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('plugins')
    <!-- Datatables -->
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>
    <!-- PDF e Excel (para botões de exportação) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/admin/datatables/shared.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('assets/js/admin/charts/courses.js') }}"></script>
    <script src="{{ asset('assets/js/admin/charts/chart-actions.js') }}"></script>
    <script>
        function imprimirGrafico(canvasId, titulo) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) {
                alert('Gráfico não encontrado.');
                return;
            }

            // Converte o canvas em imagem (PNG em base64)
            const imagem = canvas.toDataURL('image/png');

            // Abre uma nova janela
            const janela = window.open('', '_blank', 'width=900,height=650');
            janela.document.write(`
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <title>${titulo}</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; }
                body {
                    font-family: Arial, sans-serif;
                    padding: 24px;
                    text-align: center;
                }
                h1 {
                    font-size: 18px;
                    margin-bottom: 16px;
                    color: #333;
                }
                img {
                    max-width: 100%;
                    height: auto;
                }
                @media print {
                    @page { margin: 12mm; }
                }
            </style>
        </head>
        <body>
            <h1>${titulo}</h1>
            <img src="${imagem}" alt="${titulo}" onload="window.print(); window.close();">
        </body>
        </html>
    `);
            janela.document.close();
        }
    </script>
@endpush
