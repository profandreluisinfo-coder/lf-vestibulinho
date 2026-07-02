@php
    $steps = [
        1 => ['label' => 'Dados Pessoais', 'route' => 'inscription.step.personal', 'done_key' => 'step1_done'],
        2 => [
            'label' => 'Certidão de Nascimento',
            'route' => 'inscription.step.certificate',
            'done_key' => 'step2_done',
        ],
        3 => ['label' => 'Endereço', 'route' => 'inscription.step.address', 'done_key' => 'step3_done'],
        4 => ['label' => 'Dados Escolares', 'route' => 'inscription.step.academic', 'done_key' => 'step4_done'],
        5 => ['label' => 'Filiação', 'route' => 'inscription.step.family', 'done_key' => 'step5_done'],
        6 => ['label' => 'Outras Informações', 'route' => 'inscription.step.other', 'done_key' => 'step6_done'],
        7 => ['label' => 'Curso', 'route' => 'inscription.step.course', 'done_key' => 'step7_done'],
        8 => ['label' => 'Revisão', 'route' => 'inscription.step.confirm', 'done_key' => 'step8_done'],
    ];

    // Passo atual
    $currentRoute = Route::currentRouteName();
    $currentStep = collect($steps)->search(fn($s) => $s['route'] === $currentRoute);

    if ($currentStep === false) {
        $currentStep = 1;
    }

    /**
     * Descobre até qual passo o usuário pode navegar.
     *
     * Exemplo:
     * Passos 1,2,3 concluídos => libera até o 4.
     * Passos 1..7 concluídos => libera até o 8.
     */
    $maxUnlocked = 1;

    foreach ($steps as $number => $step) {
        if (session($step['done_key'], false)) {
            $maxUnlocked = min($number + 1, count($steps));
        } else {
            break;
        }
    }
@endphp

<div class="stepper-wrapper">
    <ol class="stepper">
        @foreach ($steps as $number => $step)
            @php
                $done = session($step['done_key'], false);
                $active = $number === $currentStep;
                $canVisit = $number <= $maxUnlocked;

                $classes = 'stepper-item';

                if ($active) {
                    $classes .= ' active';
                } elseif ($done) {
                    $classes .= ' done';
                } else {
                    $classes .= ' locked';
                }
            @endphp

            <li class="{{ $classes }}">
                @if ($canVisit && !$active)
                    <a href="{{ route($step['route']) }}" class="stepper-link" title="{{ $step['label'] }}">
                    @else
                        <span class="stepper-link" title="{{ $step['label'] }}">
                @endif

                <span class="stepper-bubble">
                    @if ($done && !$active)
                        <i class="bi bi-check2"></i>
                    @else
                        {{ $number }}
                    @endif
                </span>

                @if ($canVisit && !$active)
                    </a>
                @else
                    </span>
                @endif
            </li>
        @endforeach
    </ol>

    <p class="stepper-current mb-0">
        <span class="stepper-current-index">Passo {{ $currentStep }} de {{ count($steps) }}</span>
        <span class="stepper-current-label">{{ $steps[$currentStep]['label'] }}</span>
    </p>
</div>