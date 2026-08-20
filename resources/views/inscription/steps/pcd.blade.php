@extends('layouts.forms')

@section('page-title', 'Inscrição - Pessoas com Deficiência (PcD)')

@section('content')

    <form id="inscription" class="row g-4" action="{{ route('inscription.step.pcd') }}" method="POST"
        enctype="multipart/form-data">

        @csrf

        <h5 class="fw-semibold border-bottom pb-1">Pessoas com Deficiência</h5>

        <div class="form-group col-md-6 mt-3 mb-3">

            <label for="acessibilidade" class="form-label required">
                Pretende concorrer às vagas reservadas às Pessoas com Deficiência (PcD)?
            </label>

            <select name="pne" class="form-select @error('pne') is-invalid @enderror" id="accessibility">
                @php
                    $selectedPne = old('pne', session('step6.pne'));
                @endphp
                <option value="">Selecione...</option>
                <option value="1" {{ $selectedPne == 1 ? 'selected' : '' }}>Sim</option>
                <option value="2" {{ $selectedPne == 2 ? 'selected' : '' }}>Não</option>
            </select>
            @error('pne')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <div class="form-group col-md-6 mt-3 {{ $selectedPne == 1 ? '' : 'd-none' }}">

            <label for="descricaoAcessibilidade" class="form-label required">Especifique sua condição</label>

            <input list="disabilities" class="form-control @error('accessibility_description') is-invalid @enderror"
                id="accessibility_description" name="accessibility_description"
                value="{{ old('accessibility_description', session('step6.accessibility_description')) }}"
                aria-describedby="msgDisabilities">
            <small id="msgDisabilities" class="text-muted fst-italic"><i class="bi bi-info-circle"></i> Clique para escolher
                um item da lista ou descreva.</small>

            <datalist id="disabilities">
                @foreach ($disabilities as $disability)
                    <option value="{{ $disability->description }}">
                @endforeach
            </datalist>

            @error('accessibility_description')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

        </div>

        <!-- Área para anexar laudo/relatório de avaliação médico -->
        <div class="form-group col-md-12" {{ $selectedPne == 1 ? '' : 'd-none' }}>

            <div class="alert alert-warning d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    O(A) candidato(a) que, no ato da inscrição, declarar possuir necessidades especiais deverá apresentar
                    laudo ou relatório de avaliação médica que ateste a natureza e o grau da condição informada, bem como a
                    compatibilidade com as medidas de acessibilidade solicitadas. O documento deverá ser anexado
                    obrigatoriamente ao formulário de inscrição, sob pena de indeferimento do pedido.
                </div>
            </div>

            <label for="report" class="form-label required">Anexe o laudo/relatório de avaliação médico</label>
            <input type="file" name="pne_report" id="pne_report"
                class="form-control @error('pne_report') is-invalid @enderror" aria-describedby="infoPneReport">
            <small id="infoPneReport" class="form-text fst-italic text-muted"><i class="bi bi-info-circle me-1"></i>Somente
                arquivos no formato PDF são aceitos. O tamanho máximo é de 5MB.</small>

            @error('pne_report')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if (session('step6.pne_report'))
                <div class="alert alert-info mt-3 d-flex align-items-start">
                    <i class="bi bi-file-earmark-pdf-fill fs-4 me-3 flex-shrink-0"></i>

                    <div class="flex-grow-1">
                        <div class="mb-2">
                            <strong>Arquivo enviado anteriormente.</strong>
                            <a href="{{ Storage::disk('public')->url(session('step6.pne_report')) }}" target="_blank"
                                class="ms-2">
                                Visualizar PDF <i class="bi bi-box-arrow-up-right ms-1"></i>
                            </a>
                        </div>

                        <p class="mb-0 text-secondary-emphasis">
                            <i class="bi bi-info-circle me-1"></i>
                            Caso deseje avançar com a inscrição a partir deste ponto, deverá substituir o arquivo anexado.
                        </p>
                    </div>
                </div>
            @endif

        </div>

        <!-- Select Sim/Não -->
        <div class="form-group col-md-6 mt-3 {{ $selectedPne == 1 ? '' : 'd-none' }}" id="group_pne_description">

            <label for="pne_description" class="form-label required">
                Você precisa de algum tipo de atendimento com recurso de acessibilidade para o dia de prova?
            </label>

            @php
                $selectedPneDescription = old('pne_description', session('step6.pne_description'));
            @endphp

            <select name="pne_description" id="pne_description"
                class="form-select @error('pne_description') is-invalid @enderror">
                <option value="">Selecione...</option>
                <option value="1" {{ $selectedPneDescription == 1 ? 'selected' : '' }}>Sim</option>
                <option value="2" {{ $selectedPneDescription == 2 ? 'selected' : '' }}>Não</option>
            </select>

            @error('pne_description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Campo de especificação, aparece só se pne_description == 1 -->
        <div class="form-group col-md-6 mt-3 {{ $selectedPneDescription == 1 ? '' : 'd-none' }}"
            id="group_pne_description_detail">

            <label for="pne_description_detail" class="form-label required">Especifique o recurso necessário</label>

            <input list="accessibilityResources" class="form-control @error('pne_description_detail') is-invalid @enderror"
                id="pne_description_detail" name="pne_description_detail"
                value="{{ old('pne_description_detail', session('step6.pne_description_detail')) }}"
                aria-describedby="msgAccessibility">

            <small id="msgAccessibility" class="text-muted fst-italic">
                <i class="bi bi-info-circle"></i>
                Clique para escolher um item da lista ou descreva o recurso de acessibilidade necessário.
            </small>

            <datalist id="accessibilityResources">
                @foreach ($accessibilityResources as $resource)
                    <option value="{{ $resource->description }}">
                @endforeach
            </datalist>

            @error('pne_description_detail')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12 border-top pt-3">
            <button type="button" class="btn btn-sm btn-secondary">
                <i class="bi bi-arrow-left-circle me-2"></i>
                <a href="{{ route('inscription.step.family') }}" class="text-decoration-none">Voltar</a>
            </button>
            <button type="submit" class="btn btn-sm btn-primary ms-2">Avançar <i class="bi bi-arrow-right-circle ms-2"></i>
            </button>
        </div>
    </form>

@endsection

@push('scripts')
    <script src="{{ asset('assets/js/inscription/ui/pcd.js') }}"></script>
    <script src="{{ asset('assets/js/inscription/rules/pcd.js') }}"></script>
@endpush
