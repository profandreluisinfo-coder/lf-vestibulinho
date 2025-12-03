@props([
    'message' => 'Nenhum registro foi encontrado.',
    'icon' => 'fa-search',
    'submessage' => null,
    'action' => null,
    'actionMessage' => null,
    'actionText' => 'Cadastrar Novo',
    'actionRoute' => null,
    'dismissible' => true
])

<div class="text-center py-5">
    {{-- Informação --}}
    @include('components.messages', [
        'type' => 'info',
        'message' => 'Parece que as coisas andam vazias por aqui.',
        'icon' => 'bi bi-info-circle',
    ])
    <div class="py-3">
        <h5 class="text-muted mb-2">{{ $message }}</h5>

        @if ($submessage)
            <p class="text-primary mb-3"><i class="bi bi-check-circle me-2"></i>{{ $submessage }}</p>
            <p class="text-success fw-semibold mb-3"><i class="bi bi-arrow-left-right me-2"></i>{{ $actionMessage }}</p>
        @endif

        @if ($action && $actionRoute)
            <a href="{{ $actionRoute }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus me-2"></i>{{ $actionText }}
            </a>
        @endif
    </div>
</div>
</div>
