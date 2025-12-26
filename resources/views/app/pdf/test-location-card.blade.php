<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 13px;
        line-height: 1.4;
        color: #333;
    }

    .card {
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 24px;
    }

    .header {
        text-align: center;
        margin-bottom: 24px;
    }

    .header img {
        height: 50px;
        vertical-align: middle;
    }

    .section {
        margin-bottom: 16px;
    }

    .section strong {
        display: inline-block;
        width: 130px;
    }

    .alert {
        border: 1px solid #17a2b8;
        background-color: #e9f7fc;
        padding: 10px;
        border-radius: 4px;
        margin-top: 12px;
    }

    .pne {
        border: 1px solid #ffc107;
        background-color: #fff3cd;
        padding: 10px;
        border-radius: 4px;
        margin-top: 12px;
    }

    .footer {
        margin-top: 32px;
        font-size: 11px;
        color: #555;
    }
</style>

<div class="card">
    <div class="header">
        <img src="{{ public_path('assets/img/logo.webp') }}" alt="Logo">
        <h2>Processo Seletivo {{ $calendar?->year }} - Cartão do Local de Prova</h2>
    </div>

    <div class="section">
        <p><strong>Nome:</strong> {{ $exam->inscription->user->social_name ?? $exam->inscription->user->name }}</p>
        <p><strong>CPF:</strong> {{ $exam->inscription->user->cpf }}</p>
        <p><strong>Local:</strong> {{ $exam->location->name }}</p>
        <p><strong>Endereço:</strong> {{ $exam->location->address }}</p>
        <p><strong>Sala:</strong> {{ $exam->room_number }}
            @if ($exam->pne)
                - Sala de Atendimento Especializado
            @endif
        </p>
        <p><strong>Data:</strong> {{ \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') }}</p>
        <p><strong>Horário:</strong> {{ \Carbon\Carbon::parse($exam->exam_time)->format('H:i') }}</p>
    </div>

    @if ($exam->inscription->user->pne)
        <div class="pne">
            <strong>Atenção:</strong> Este candidato declarou possuir deficiência e deve ser alocado em sala com
            acessibilidade.
        </div>
    @endif

    <div class="alert">
        <strong>Instruções:</strong>
        <ul style="margin: 4px 0 0 16px; padding-left: 0;">
            <li>Chegue com 30 minutos de antecedência;</li>
            <li>Leve documento com foto e caneta esferográfica azul ou preta;</li>
            <li>Não será permitido o uso de aparelhos eletrônicos;</li>
        </ul>
    </div>
</div>