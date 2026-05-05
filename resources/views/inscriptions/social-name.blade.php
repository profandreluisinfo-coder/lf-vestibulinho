@extends('layouts.admin.master')

@section('page-title', config('app.name') . ' ' . $calendar?->year . ' | Nome Social')

@push('datatable-styles')
    <!-- datatables -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('dash-content')

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0"><i class="bi bi-person me-2"></i>Candidatos com Nome Social</h5>
        </div>
        @if (!$users->isEmpty())
            <div class="alert alert-info d-flex align-items-center shadow-sm alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <div class="d-flex align-items-center fw-semibold">
                    <i class="bi bi-info-circle fs-5 me-2"></i>
                    Para encontrar um registro específico, digite na caixa de pesquisa o número da inscrição, ou qualquer parte do nome
                    do candidato.
                </div>
            </div>
        @endif
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
            <table id="subscribers" class="table table-striped table-hover freezed-table caption-top align-middle">
                <caption>{{ config('app.name') }} {{ $calendar->year }} - Lista de Candidatos com Nome Social</caption>
                <thead class="table-success text-center">
                    <tr>
                        <th scope="col">Inscrição</th>
                        <th scope="col">Candidato</th>
                        <th scope="col">Nome Social</th>
                        <th scope="col">Autorização</th>
                        <th scope="col">Situação</th>
                        <th scope="col">Observações</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @forelse ($users as $user)
                        <tr>
                            <th scope="row">{{ $user->inscription?->id }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->social_name }}</td>
                            <td>
                                @if ($user->authorization)
                                    <a href="{{ asset('storage/' . $user->authorization) }}"
                                        class="btn btn-sm btn-link text-decoration-none" target="_blank">
                                        <i class="bi bi-eye"></i> Visualizar
                                    </a>
                                @else
                                    Não apresentou
                                @endif
                            </td>
                            <td>
                                @if (!$user->authorization)
                                    Não apresentou
                                @elseif ($user->authorization_accepted === null)
                                    Pendente de análise
                                @elseif ($user->authorization_accepted == 1)
                                    Deferido
                                @elseif ($user->authorization_accepted == 2)
                                    Indeferido                                    
                                @endif
                            </td>
                            <td>
                                @if ($user->authorization_rejection_reason)
                                    {{ $user->authorization_rejection_reason }}
                                @endif
                            </td>
                            <td>
                                @if ($user->authorization && $user->authorization_accepted === null)
                                    <button class="btn btn-sm btn-success accept-social-name mb-1"
                                        data-url="{{ route('deferrals.accept.authorization', $user->id) }}"
                                        title="Deferir">

                                        <i class="bi bi-check me-1"></i> Deferir
                                    </button>

                                    <button class="btn btn-sm btn-danger reject-social-name"
                                        data-url="{{ route('deferrals.reject.authorization', $user->id) }}"
                                        title="Indeferir">

                                        <i class="bi bi-x me-1"></i> Indeferir
                                    </button>
                                @endif

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Nenhum candidato encontrado</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
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
    <script src="{{ asset('assets/js/datatables/social-name.js') }}"></script>
    <script src="{{ asset('assets/js/ui/registration/popover.js') }}"></script>
@endpush
