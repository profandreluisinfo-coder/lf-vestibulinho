@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF ' . $process?->year . ' - Configurações do Sistema')

@push('styles')
    <style>
        .system-card {
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }

        .system-card:hover {
            box-shadow: 0 0.75rem 1.5rem rgba(0, 0, 0, 0.18) !important;
            transform: translateY(-3px);
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-gear text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Configurações do Sistema</h6>
            </div>
        </div>

        <div class="row g-4">

            {{-- Card: Redefinir Sistema --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card system-card border-danger shadow-sm h-100">

                    <div class="card-body d-flex flex-column">
                        <div class="mb-3">
                            <div
                                class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-database-dash fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold text-danger">
                            Redefinir Sistema
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Remove usuários, inscrições, calendário atual e eventos vinculados ao processo seletivo.
                        </p>

                        <div class="alert alert-danger py-2 px-3 small mb-3">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            Esta ação não poderá ser desfeita.
                        </div>

                        <button class="btn btn-danger btn-sm w-100"
                            id="btn-reset-system"
                            onclick="resetSystem()">
                            <i class="bi bi-trash me-1"></i>
                            Redefinir Sistema
                        </button>
                    </div>

                </div>
            </div>

            {{-- Exemplo de outros cards --}}
            {{-- <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="mb-3">
                            <div
                                class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-calendar-event fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold">
                            Calendário
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Gerencie o calendário do processo seletivo e os eventos relacionados.
                        </p>

                        <button class="btn btn-primary btn-sm w-100">
                            Gerenciar
                        </button>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="mb-3">
                            <div
                                class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-file-earmark-text fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold">
                            Edital
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Atualize os arquivos do edital e documentos complementares.
                        </p>

                        <button class="btn btn-success btn-sm w-100">
                            Configurar
                        </button>

                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="mb-3">
                            <div
                                class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 55px; height: 55px;">
                                <i class="bi bi-question-circle fs-4"></i>
                            </div>
                        </div>

                        <h5 class="card-title fw-bold">
                            FAQs
                        </h5>

                        <p class="card-text text-muted small flex-grow-1">
                            Gerencie perguntas frequentes e informações de ajuda aos candidatos.
                        </p>

                        <button class="btn btn-warning btn-sm w-100 text-white">
                            Editar FAQs
                        </button>

                    </div>
                </div>
            </div> --}}

        </div> <!-- End of row g-4 -->

    </div>
@endsection

@push('scripts')
    <script>
        const resetUrl = "{{ route('admin.system.reset') }}";
    </script>

    <script src="{{ asset('assets/js/swa/system/reset.js') }}"></script>
@endpush
