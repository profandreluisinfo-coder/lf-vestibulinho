@extends('layouts.forms.master')

@section('page-title', 'Inscrição | Confirmar Dados')

@php
  $etapas = collect(range(1, 7))->every(fn($i) => session("step{$i}") === null);
@endphp

@section('forms')

  @php
    $degrees = [
        1 => 'PADRASTO',
        2 => 'MADRASTA',
        3 => 'AVÔ(Ó)',
        4 => 'TIO(A)',
        5 => 'IRMÃO(Ã)',
        6 => 'PRIMO(A)',
        7 => 'TIO(A)',
        8 => 'OUTRO',
    ];
  @endphp

  <div class="mb-4 text-center">
    <p class="fw-bold">Por favor, confirme os dados abaixo antes de finalizar sua inscrição.</p>
  </div>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-person-vcard"></i> Identificação do Candidato</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><i class="bi bi-credit-card"></i> <strong>CPF:</strong> {{ $step1['cpf'] }}</li>
      <li class="list-group-item"><i class="bi bi-person"></i> <strong>Nome:</strong> {{ $step1['name'] }}</li>
      @if ($step1['socialNameOption'] == 1)
        <li class="list-group-item"><i class="bi bi-person-bounding-box"></i> <strong>Nome social:</strong>
          {{ $step1['social_name'] }}</li>
      @endif
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-card-text"></i> Documentos Pessoais</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Nacionalidade:</strong>
        {{ $step1['nationality'] == 1 ? 'BRASILEIRA' : 'ESTRANGEIRA' }}</li>
      <li class="list-group-item">
        <strong>Tipo de documento:</strong>
        @php
          $doc = match ($step1['doc_type']) {
              '1' => 'RG - REGISTRO GERAL',
              '2' => 'CIN - CARTEIRA DE IDENTIDADE NACIONAL',
              '3' => 'RNE - REGISTRO NACIONAL DE ESTRANGEIRO',
              default => $step1['doc_type'] . ' - ERRO AO IDENTIFICAR',
          };
        @endphp
        {{ $doc }}
      </li>
      <li class="list-group-item"><strong>Nº Documento:</strong> {{ $step1['doc_number'] }}</li>
      <li class="list-group-item"><strong>Nascimento:</strong>
        {{ \Carbon\Carbon::parse($step1['birth'])->format('d/m/Y') }}</li>
      <li class="list-group-item"><strong>Gênero: </strong>
        {{ match ($step1['gender']) {
            '1' => 'MASCULINO',
            '2' => 'FEMININO',
            '3' => 'OUTRO',
            default => 'PREFIRO NÃO INFORMAR',
        } }}
      </li>
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-file-earmark-text"></i> Certidão de Nascimento</legend>
    <ul class="list-group list-group-flush">
      @if ($step2['certificateModel'] == '1')
        <li class="list-group-item"><strong>Nº Certidão:</strong> {{ $step2['new_number'] }}</li>
      @else
        <li class="list-group-item"><strong>Folhas:</strong> {{ $step2['fls'] }}</li>
        <li class="list-group-item"><strong>Livro:</strong> {{ $step2['book'] }}</li>
        <li class="list-group-item"><strong>Nº Certidão:</strong> {{ $step2['old_number'] }}</li>
        <li class="list-group-item"><strong>Município:</strong> {{ $step2['municipality'] }}</li>
      @endif
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-telephone"></i> Contato</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>E-mail:</strong> {{ Auth::user()->email }}</li>
      <li class="list-group-item"><strong>Telefone:</strong> {{ $step1['phone'] }}</li>
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-geo-alt"></i> Endereço</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>CEP:</strong> {{ $step3['zip'] }}</li>
      <li class="list-group-item"><strong>Rua:</strong> {{ $step3['street'] }}</li>
      @if ($step3['number'])
        <li class="list-group-item"><strong>Nº:</strong> {{ $step3['number'] }}</li>
      @endif
      @if ($step3['complement'])
        <li class="list-group-item"><strong>Complemento:</strong> {{ $step3['complement'] }}</li>
      @endif
      <li class="list-group-item"><strong>Bairro:</strong> {{ $step3['burgh'] }}</li>
      <li class="list-group-item"><strong>Cidade:</strong> {{ $step3['city'] }}</li>
      <li class="list-group-item"><strong>Estado:</strong> {{ $step3['state'] }}</li>
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-book"></i> Escolaridade</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Escola:</strong> {{ $step4['school_name'] }}</li>
      <li class="list-group-item"><strong>RA:</strong> {{ $step4['school_ra'] }}</li>
      <li class="list-group-item"><strong>Cidade:</strong> {{ $step4['school_city'] }}</li>
      <li class="list-group-item"><strong>Estado:</strong> {{ $step4['school_state'] }}</li>
      <li class="list-group-item"><strong>Ano de conclusão:</strong> {{ $step4['school_year'] }}</li>
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-people"></i> Filiação / Responsável Legal</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Mãe:</strong> {{ $step5['mother'] }}</li>
      @if ($step5['mother_phone'])
        <li class="list-group-item"><strong>Telefone Mãe:</strong> {{ $step5['mother_phone'] }}</li>
      @endif
      @if ($step5['father'])
        <li class="list-group-item"><strong>Pai:</strong> {{ $step5['father'] }}</li>
      @endif
      @if ($step5['father_phone'])
        <li class="list-group-item"><strong>Telefone Pai:</strong> {{ $step5['father_phone'] }}</li>
      @endif
      @if ($step5['responsible'])
        <li class="list-group-item"><strong>Responsável:</strong> {{ $step5['responsible'] }}</li>
        <li class="list-group-item"><strong>Parentesco:</strong> {{ $degrees[$step5['degree']] ?? '' }}</li>
        @if ($step5['kinship'])
          <li class="list-group-item"><strong>Descrição:</strong> {{ $step5['kinship'] }}</li>
        @endif
        <li class="list-group-item"><strong>Telefone:</strong> {{ $step5['responsible_phone'] }}</li>
      @endif
      <li class="list-group-item"><strong>E-mail pais/responsável:</strong> {{ $step5['parents_email'] }}</li>
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-universal-access"></i> Educação Especial</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Elegível?</strong> {{ $step6['accessibility'] == 1 ? 'SIM' : 'NÃO' }}</li>
      @if ($step6['accessibility'] == '1')
        <li class="list-group-item"><strong>Descrição:</strong> {{ $step6['accessibility_description'] }}</li>
      @endif
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-award"></i> Programas Sociais</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Bolsa-Família?</strong> {{ $step6['social_program'] == 1 ? 'SIM' : 'NÃO' }}
      </li>
      @if ($step6['social_program'] == 1)
        <li class="list-group-item"><strong>NIS:</strong> {{ $step6['nis'] }}</li>
      @endif
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-heart-pulse"></i> Outras Informações</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item"><strong>Problema de saúde ou alergia?</strong>
        {{ $step6['health'] == 1 ? 'SIM' : 'NÃO' }}</li>
      @if ($step6['health'] == 1)
        <li class="list-group-item"><strong>Descrição:</strong> {{ $step6['health_issue'] }}</li>
      @endif
    </ul>
  </fieldset>

  <fieldset class="mb-2 rounded border p-2">
    <legend class="border-bottom pb-1"><i class="bi bi-journal-bookmark"></i> Pesquisa de intenção de curso</legend>
    <ul class="list-group list-group-flush">
      <li class="list-group-item">{{ \App\Models\Course::getDescription($step7['course_id']) }}</li>
    </ul>
  </fieldset>

  <form id="finalize-inscription" class="d-flex justify-content-center mt-3" action="{{ route('step.finalize') }}"
    method="POST">
    @csrf
    <button type="button" class="btn btn-sm btn-secondary me-2">
      <i class="bi bi-arrow-left-circle me-2"></i>
      <a href="{{ route('step.course') }}" class="text-decoration-none">Voltar</a>
    </button>

    <button type="button" class="btn btn-sm btn-success" onclick="confirmFinalize()">
      Confirmar <i class="bi bi-check-circle ms-2"></i>
    </button>
  </form>

@endsection

@push('scripts')
  <script src="{{ asset('assets/swa/finalize.js') }}"></script>
@endpush