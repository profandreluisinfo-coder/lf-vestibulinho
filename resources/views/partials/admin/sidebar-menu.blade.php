<ul class="nav nav-pills flex-column mb-auto">
    {{-- Início --}}
    <li class="nav-item mb-1">
        <a href="{{ route('dash.admin.home') }}" class="nav-link text-light">
            <i class="bi bi-house-door me-2"></i> Início
        </a>
    </li>

    {{-- Vestibulinho --}}
    <li class="nav-item mb-1">
        <button class="nav-link text-light w-100 text-start d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse" data-bs-target="#menuVestibulinho" aria-expanded="false">
            <span><i class="bi bi-calendar-event me-2"></i> Vestibulinho</span>
            <i class="bi bi-chevron-down small"></i>
        </button>
        <div class="collapse ps-3" id="menuVestibulinho">
            <a href="{{ route(
'app.calendar.index') }}" class="nav-link text-light">Calendário</a>
            <a href="{{ route('app.courses.index') }}" class="nav-link text-light">Cursos</a>
            <a href="{{ route('app.notices.index') }}" class="nav-link text-light">Edital</a>
            <a href="{{ route('app.faqs.index') }}" class="nav-link text-light">Registrar FAQ</a>
            <a href="{{ route('app.archives.index') }}" class="nav-link text-light">Acervo de Provas</a>
            <a href="{{ route('app.system.index') }}" class="nav-link text-light">Redefinir Dados</a>
        </div>
    </li>

    {{-- Usuários --}}
    <li class="nav-item mb-1">
        <button class="nav-link text-light w-100 text-start d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse" data-bs-target="#menuUsuarios" aria-expanded="false">
            <span><i class="bi bi-people me-2"></i> Usuários</span>
            <i class="bi bi-chevron-down small"></i>
        </button>
        <div class="collapse ps-3" id="menuUsuarios">
            <a href="{{ route('users.index') }}" class="nav-link text-light">Lista de Usuários</a>
        </div>
    </li>

    {{-- Inscrições --}}
    <li class="nav-item mb-1">
        <button class="nav-link text-light w-100 text-start d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse" data-bs-target="#menuInscricoes" aria-expanded="false">
            <span><i class="bi bi-file-earmark-text me-2"></i> Inscrições</span>
            <i class="bi bi-chevron-down small"></i>
        </button>
        <div class="collapse ps-3" id="menuInscricoes">
            <a href="{{ route('app.inscriptions.index') }}" class="nav-link text-light">Lista Geral</a>
            <a href="{{ route('app.inscriptions.pcd') }}" class="nav-link text-light">Pessoas com Deficiência</a>
            <a href="{{ route('app.inscriptions.social.name') }}" class="nav-link text-light">Nome Social</a>
        </div>
    </li>

    {{-- Provas --}}
    <li class="nav-item mb-1">
        <button class="nav-link text-light w-100 text-start d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse" data-bs-target="#menuProvas" aria-expanded="false">
            <span><i class="bi bi-journal-check me-2"></i> Provas</span>
            <i class="bi bi-chevron-down small"></i>
        </button>
        <div class="collapse ps-3" id="menuProvas">
            <a href="{{ route('app.exam.index') }}" class="nav-link text-light">Locais de Prova</a>
            <a href="{{ route('app.exam.index') }}" class="nav-link text-light">Agendar Prova</a>
            <a href="{{ route('export.users') }}" class="nav-link text-light">Gerar Planilha de Notas</a>
        </div>
    </li>

    {{-- Resultados --}}
    <li class="nav-item mb-1">
        <button class="nav-link text-light w-100 text-start d-flex justify-content-between align-items-center"
            data-bs-toggle="collapse" data-bs-target="#menuResultados" aria-expanded="false">
            <span><i class="bi bi-bar-chart-line me-2"></i> Resultados</span>
            <i class="bi bi-chevron-down small"></i>
        </button>
        <div class="collapse ps-3" id="menuResultados">
            <a href="{{ route('app.import.home') }}" class="nav-link text-light">Importar Notas</a>
            <a href="{{ route('app.results.index') }}" class="nav-link text-light">Classificação Geral</a>
            <a href="{{ route('app.calls.create') }}" class="nav-link text-light">Chamadas</a>
        </div>
    </li>

    {{-- Configurações e Logout --}}
    <hr class="border-light opacity-50 my-3">
    <li class="nav-item">
        <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="nav-link text-light border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Sair
            </button>
        </form>
    </li>
</ul>