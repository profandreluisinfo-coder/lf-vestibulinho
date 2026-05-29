@php
    $steps = [
        1 => ['label' => 'Dados Pessoais',          'route' => 'step.personal',     'done_key' => 'step1_done'],
        2 => ['label' => 'Certidão de Nascimento',  'route' => 'step.certificate',  'done_key' => 'step2_done'],
        3 => ['label' => 'Endereço',                'route' => 'step.address',      'done_key' => 'step3_done'],
        4 => ['label' => 'Dados Escolares',         'route' => 'step.academic',     'done_key' => 'step4_done'],
        5 => ['label' => 'Filiação',                'route' => 'step.family',       'done_key' => 'step5_done'],
        6 => ['label' => 'Outras Informações',      'route' => 'step.other',       'done_key' => 'step6_done'],
        7 => ['label' => 'Curso',                   'route' => 'step.course',       'done_key' => 'step7_done'],
        8 => ['label' => 'Revisão',                 'route' => 'step.confirm',       'done_key' => 'step8_done'],
    ];

    // Determina o passo atual pela rota
    $currentRoute = Route::currentRouteName();
    $currentStep  = collect($steps)->search(fn($s) => $s['route'] === $currentRoute) ?? 1;
@endphp

<div class="stepper-wrapper mb-4">
    <ol class="stepper">
        @foreach ($steps as $number => $step)
            @php
                $done    = session($step['done_key'], false);
                $active  = $number === $currentStep;
                $canVisit = $done || $active || ($number > 1 && session($steps[$number - 1]['done_key'], false));

                $classes = 'stepper-item';
                if ($active)    $classes .= ' active';
                elseif ($done)  $classes .= ' done';
                else            $classes .= ' locked';
            @endphp

            <li class="{{ $classes }}">
                @if ($done && !$active && $canVisit)
                    <a href="{{ route($step['route']) }}" class="stepper-link">
                @else
                    <span class="stepper-link">
                @endif

                <span class="stepper-bubble">
                    @if ($done && !$active)
                        <i class="bi bi-check2"></i>
                    @else
                        {{ $number }}
                    @endif
                </span>

                <span class="stepper-label">{{ $step['label'] }}</span>

                @if ($done && !$active && $canVisit)
                    </a>
                @else
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</div>