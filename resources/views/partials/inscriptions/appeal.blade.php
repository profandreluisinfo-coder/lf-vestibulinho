{{-- Andamento do recurso do candidato (só para pedido indeferido). Recebe $type: 'pne' ou 'lgbt'. --}}

@php($appeal = $user->appealOf($type))

@if (! $appeal)

    {{-- Ainda sem recurso: só avisa enquanto o candidato não foi alocado em prova --}}
    @if (! $user->inscription?->exam_result)
        <div class="modality-note modality-pending d-flex align-items-start gap-2">
            <i class="bi bi-info-circle mt-1"></i>
            <div>
                Você pode entregar um <strong>recurso</strong> na secretaria da escola,
                dentro do prazo previsto no edital.
            </div>
        </div>
    @endif

@elseif ($appeal->isPending())

    {{-- Recurso recebido pela secretaria --}}
    <div class="modality-note modality-pending d-flex align-items-start gap-2">
        <i class="bi bi-hourglass-split mt-1"></i>
        <div>
            Recurso nº <strong>{{ $appeal->protocol }}</strong> recebido e
            <strong>em análise</strong>.
        </div>
    </div>

@elseif ($appeal->isRejected())

    {{-- Recurso indeferido --}}
    <div class="modality-note modality-ac d-flex align-items-start gap-2">
        <i class="bi bi-x-circle-fill mt-1"></i>
        <div>
            <div>Recurso nº <strong>{{ $appeal->protocol }}</strong> indeferido.</div>
            @if ($appeal->observations)
                <div class="mt-1"><strong>Motivo:</strong> {{ $appeal->observations }}</div>
            @endif
        </div>
    </div>

@endif