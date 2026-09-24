@extends('layouts.admin')

@section('page-title', 'Vestibulinho LF ' . $process?->year . ' - Backups do Sistema')

@section('content')
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-gear text-muted"></i>
                <h6 class="mb-0 text-muted fw-normal">Lista de Backups do Sistema</h6>
            </div>

            <form id="form-gerar-backup" action="{{ route('admin.system.backups.store') }}" method="POST">
                @csrf
                <button type="submit" id="btn-gerar-backup" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i>
                    <span id="btn-gerar-backup-texto">Gerar novo backup</span>
                </button>
            </form>
        </div>

        <p class="text-muted mb-4">
            Aqui você pode visualizar, baixar e excluir os backups do sistema. Eles também são gerados automaticamente antes do
            processo de redefinição do sistema e ficam armazenados no servidor. Certifique-se de fazer o download dos backups
            importantes antes de excluí-los.
        </p>

        <div class="row g-4">

            @forelse ($backups as $backup)
                <div class="col-12 col-sm-6 col-lg-3">
                    <div class="card system-card border-primary shadow-sm h-100">

                        <div class="card-body d-flex flex-column">
                            <div class="mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 55px; height: 55px;">
                                    <i class="bi bi-file-earmark-zip fs-4"></i>
                                </div>
                            </div>

                            <h5 class="card-title fw-bold text-primary text-truncate" title="{{ $backup['filename'] }}">
                                {{ $backup['filename'] }}
                            </h5>

                            <p class="card-text text-muted small flex-grow-1">
                                {{ $backup['size'] }} &bull; {{ $backup['date'] }}
                            </p>

                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.system.backups.download', ['filename' => $backup['filename']]) }}"
                                    class="btn btn-primary btn-sm w-100" title="Baixar Backup">
                                    <i class="bi bi-download me-1"></i>
                                    Baixar
                                </a>

                                <form action="{{ route('admin.system.backups.delete', ['filename' => $backup['filename']]) }}"
                                    method="POST" class="w-100 delete-backup-form">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-danger btn-sm w-100" title="Excluir Backup">
                                        <i class="bi bi-trash me-1"></i>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Nenhum backup encontrado.
                    </div>
                </div>
            @endforelse
        </div> <!-- End of row g-4 -->

    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.delete-backup-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                Swal.fire({
                    title: 'Tem certeza?',
                    text: 'Este backup será excluído permanentemente.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('form-gerar-backup').addEventListener('submit', function() {
            const botao = document.getElementById('btn-gerar-backup');
            const texto = document.getElementById('btn-gerar-backup-texto');

            // Desabilita o botão, pra evitar cliques duplicados
            botao.disabled = true;

            // Troca o conteúdo: ícone giratório + texto "Gerando..."
            botao.innerHTML = `
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                Gerando backup...
            `;
        });
    </script>
@endpush