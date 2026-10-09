<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="offcanvasMenuLabel">
            <i class="bi bi-grid-3x3-gap me-2"></i>Menu Rápido
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
    </div>
    <div class="offcanvas-body">

        {{-- Seção de Ações Rápidas --}}
        <div class="mb-4">
            <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Ações
                Rápidas</h6>
            <div class="row g-3">
                <div class="col-6">
                    <a href="{{ route('admin.process.show') }}" class="offcanvas-card">
                        <div class="offcanvas-card-icon" style="background: #dbeafe; color: #2563eb;">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <span>Calendário</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.users.index') }}" class="offcanvas-card">
                        <div class="offcanvas-card-icon" style="background: #fce7f3; color: #db2777;">
                            <i class="bi bi-people"></i>
                        </div>
                        <span>Usuários</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.inscriptions.index') }}" class="offcanvas-card">
                        <div class="offcanvas-card-icon" style="background: #d1fae5; color: #10b981;">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <span>Inscrições</span>
                    </a>
                </div>
                <div class="col-6">
                    <a href="{{ route('admin.reports.classification') }}" class="offcanvas-card">
                        <div class="offcanvas-card-icon" style="background: #fef3c7; color: #f59e0b;">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <span>Classificação</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Seção de Links Úteis --}}
        <div class="mb-4">
            <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">Links Úteis
            </h6>
            <div class="list-group list-group-flush">
                <a href="{{ route('admin.courses.index') }}"
                    class="list-group-item list-group-item-action border-0 px-0">
                    <i class="bi bi-book me-2"></i>Gerenciar Cursos
                </a>
                <a href="{{ route('admin.faqs.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                    <i class="bi bi-question-circle me-2"></i>Registrar FAQ
                </a>
                <a href="{{ route('admin.exam.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                    <i class="bi bi-calendar-check me-2"></i>Agendar Prova
                </a>
                <a href="{{ route('admin.reports.classification') }}"
                    class="list-group-item list-group-item-action border-0 px-0">
                    <i class="bi bi-list-ol me-2"></i>Ver Classificação
                </a>
                <a href="{{ route('admin.calls.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                    <i class="bi bi-broadcast-pin me-2"></i>Convocação para matrícula
                </a>
            </div>
        </div>

        {{-- Seção de Estatísticas --}}
        <div class="mb-4">
            <h6 class="text-muted mb-3 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                Estatísticas Rápidas</h6>
            <div class="d-flex flex-column gap-2">
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div>
                        <div class="text-muted" style="font-size: 12px;">Total de Inscrições</div>
                        <div class="fw-bold" style="font-size: 20px; color: #1e293b;">{{ $totalInscriptions }}
                        </div>
                    </div>
                    <i class="bi bi-file-earmark-text" style="font-size: 32px; color: #cbd5e1;"></i>
                </div>
                <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                    <div>
                        <div class="text-muted" style="font-size: 12px;">Usuários Sem Inscrição</div>
                        <div class="fw-bold" style="font-size: 20px; color: #1e293b;">
                            {{ $usersWithoutInscription }}</div>
                    </div>
                    <i class="bi bi-people" style="font-size: 32px; color: #cbd5e1;"></i>
                </div>
            </div>
        </div>
    </div>
</div>
