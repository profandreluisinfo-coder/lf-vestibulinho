@extends('layouts.forms')

@section('page-title', 'Inscrição | Dados Pessoais')

@section('content')

    <div class="wrapper">

        <div class="section-title mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4>
                    <i class="bi bi-list-check me-2"></i>
                    Formulário de Inscrição
                </h4>

                <h6 class="fw-semibold text-danger fst-italic">
                    * campo obrigatório
                </h6>
            </div>

            <div class="divider-teal"></div>
        </div>

        @include('partials.forms.stepper')

        <form id="inscription" class="row m-0 g-4" action="{{ route('inscription.step.personal') }}" method="POST"
            enctype="multipart/form-data">
            @csrf

            <h5 class="fw-semibold border-bottom pb-1">Dados Pessoais</h5>
            <div class="form-group col-md-3">

                <label for="cpf" class="form-label required">CPF do candidato:</label>
                <input type="text" class="form-control @error('cpf') is-invalid @enderror" id="cpf" name="cpf"
                    value="{{ old('cpf', session('step1.cpf')) }}">

                @error('cpf')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="name" class="form-label required">Nome completo:</label>
                <input name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', session('step1.name')) }}">

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label for="birth" class="form-label required">Nascimento:</label>
                <input type="date" class="form-control @error('birth') is-invalid @enderror" id="birth"
                    name="birth" value="{{ old('birth', session('step1.birth')) }}">

                @error('birth')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label for="gender" class="form-label required">Gênero:</label>
                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                    <option value="">Selecione...</option>
                    <option value="1" {{ old('gender', session('step1.gender')) == '1' ? 'selected' : '' }}>Masculino
                    </option>
                    <option value="2" {{ old('gender', session('step1.gender')) == '2' ? 'selected' : '' }}>Feminino
                    </option>
                    <option value="3" {{ old('gender', session('step1.gender')) == '3' ? 'selected' : '' }}>Outro
                    </option>
                    <option value="4" {{ old('gender', session('step1.gender')) == '4' ? 'selected' : '' }}>Prefiro
                        não informar</option>
                </select>

                @error('gender')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-12">
                <label class="form-label required">
                    Deseja ser tratado pelo nome social/afetivo? <i class="bi bi-info-circle fs-6 text-primary ms-1"
                        data-bs-toggle="modal" data-bs-target="#modalNomeSocial"
                        title="Clique para obter ajuda sobre o uso do nome social"></i>
                </label>
                <div class="mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('social_name_option') is-invalid @enderror" type="radio"
                            name="social_name_option" id="radioYes" value="1"
                            {{ old('social_name_option', session('step1.social_name_option')) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="socialNameOption">
                            Sim, quero ser tratado(a) pelo nome social/afetivo
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input @error('social_name_option') is-invalid @enderror" type="radio"
                            name="social_name_option" id="radioNo" value="2"
                            {{ old('social_name_option', session('step1.social_name_option')) != 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="socialNameOption">
                            Não, não ser tratado(a) pelo nome social/afetivo
                        </label>
                    </div>
                </div>

                @error('social_name_option')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div id="socialName" class="form-group col-md-12 d-none">
                <label for="social_name" class="form-label">Nome social/afetivo:</label>
                <input name="social_name" id="social_name" class="form-control @error('social_name') is-invalid @enderror"
                    value="{{ old('social_name', session('step1.social_name')) }}" aria-describedby="socialName">
                <small id="socialName" class="form-text fst-italic text-muted"><i class="bi bi-info-circle me-1"></i>Leia
                    atentamente o item 4.10 do <a href="{{ asset('storage/' . $process?->edital) }}"
                        title="Clique aqui para abrir o edital" target="_blank">edital</a>.</small>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Área para anexar autorização do responsável legal -->
            <div id="authorizationDiv" class="form-group col-md-12 d-none">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        O(a) candidato(a) que desejar utilizar nome social/afetivo neste Processo Seletivo poderá fazê-lo,
                        desde que obtenha autorização expressa dos responsáveis legais</u>. A autorização deverá ser enviada
                        em anexo ao formulário de inscrição.
                    </div>
                </div>

                @if (session('step1.authorization'))
                    <div class="alert alert-info mt-3">
                        <p>
                            <strong>Arquivo já enviado.</strong>
                            <a href="{{ Storage::disk('public')->url(session('step1.authorization')) }}" target="_blank">
                                Visualizar PDF
                            </a>
                        </p>
                        <p class="mb-0">
                            Caso deseje avançar com a inscrição a partir deste ponto, deverá substituir o arquivo anexado.
                        </p>
                    </div>
                @endif

                <label for="authorization" class="form-label required">Autorização do responsável legal para uso do nome
                    social.</label>
                <input type="file" name="authorization" id="authorization"
                    class="form-control @error('authorization') is-invalid @enderror"
                    aria-describedby="infoAuthorization">
                <small id="infoAuthorization" class="form-text fst-italic text-muted"><i
                        class="bi bi-info-circle me-1"></i>Somente arquivos no formato PDF são aceitos. O tamanho máximo é
                    de 5MB.</small>

                @error('authorization')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label for="nationality" class="form-label required">Nacionalidade:</label>
                @php
                    $selectedNationality = old('nationality', session('step1.nationality'));
                @endphp
                <select class="form-select @error('nationality') is-invalid @enderror" id="nationality"
                    name="nationality">
                    <option value="">Selecione...</option>
                    <option value="1" {{ $selectedNationality == 1 ? 'selected' : '' }}>Brasileira</option>
                    <option value="2" {{ $selectedNationality == 2 ? 'selected' : '' }}>Brasileira naturalizada</option>
                    <option value="3" {{ $selectedNationality == 3 ? 'selected' : '' }}>Estrangeira</option>
                    <option value="4" {{ $selectedNationality == 4 ? 'selected' : '' }}>Portuguesa (com estatuto de igualdade)</option>
                </select>

                @error('nationality')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label for="doc_type" class="form-label required">Tipo de documento:</label>
                @php
                    $selectedDocType = old('doc_type', session('step1.doc_type'));
                @endphp
                <select class="form-select @error('doc_type') is-invalid @enderror" id="doc_type" name="doc_type">
                    <option value="">Selecione...</option>
                    <option value="1" {{ $selectedDocType == 1 ? 'selected' : '' }}>RG - Registro Geral</option>
                    <option value="2" {{ $selectedDocType == 2 ? 'selected' : '' }}>CIN - Carteira de Identidade Nacional</option>
                    <option value="3" {{ $selectedDocType == 3 ? 'selected' : '' }}>RNM - Registro Nacional Migratório</option>
                </select>
            </div>
            <div class="form-group col-md-3">
                <label for="doc_number" class="form-label required">Nº do documento:</label>
                <input type="text" class="form-control @error('doc_number') is-invalid @enderror" id="doc_number"
                    name="doc_number" minlength="7" maxlength="11"
                    value="{{ old('doc_number', session('step1.doc_number')) }}">

                @error('doc_number')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label for="expedition_date" class="form-label required">Data de expedição:</label>
                <input type="date" class="form-control @error('expedition') is-invalid @enderror" id="expedition"
                    name="expedition" value="{{ old('expedition', session('step1.expedition')) }}">

                @error('expedition')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group col-md-3">
                <label for="phone" class="form-label required">Telefone do candidato:</label>
                <input type="text" class="form-control phone-mask @error('phone') is-invalid @enderror"
                    id="phone" name="phone" value="{{ old('phone', session('step1.phone')) }}">

                @error('phone')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="col-12 border-top pt-3">
                <button type="submit" class="btn btn-primary btn-sm w-auto">Avançar <i
                        class="bi bi-arrow-right-circle ms-2"></i></button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="modalNomeSocial" tabindex="-1" aria-labelledby="modalNomeSocialLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-primary text-light">
                    <h5 class="modal-title text-light" id="modalNomeSocialLabel">
                        <i class="bi bi-info-circle me-2"></i> O que é nome social e nome afetivo
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                        <i class="bi bi-info-circle-fill me-3 fs-4 flex-shrink-0"></i>
                        <div>
                            <strong>Nome social é:</strong> designação pela qual a pessoa travesti ou transexual se
                            identifica e é socialmente reconhecida (como garantido pelo
                            <a class="text-decoration-none fw-semibold"
                                href="https://www.planalto.gov.br/ccivil_03/_ato2015-2018/2016/decreto/d8727.htm"
                                target="_blank" title="DECRETO Nº 8.727, DE 28 DE ABRIL DE 2016">
                                Decreto nº 8.727/2016
                            </a>).
                        </div>
                    </div>
                    <div class="alert alert-info d-flex align-items-start" role="alert">
                        <i class="bi bi-heart-fill me-3 fs-4 flex-shrink-0"></i>
                        <div>
                            <strong>Nome afetivo é:</strong> aquele que os responsáveis legais pela criança ou
                            adolescente pretendem tornar definitivo quando das alterações da respectiva certidão de
                            nascimento (conforme
                            <a class="text-decoration-none fw-semibold"
                                href="https://www.al.sp.gov.br/repositorio/legislacao/lei/2018/lei-16785-03.07.2018.html"
                                target="_blank" title="LEI Nº 16.785, DE 03 DE JULHO DE 2018">
                                Lei nº 16.785/2018
                            </a>).
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('plugins')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/cleave/masks.js') }}"></script>
    <script src="{{ asset('assets/js/inscription/rules/personal.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const socialNameOption1 = document.getElementById('radioYes');
            const socialNameOption2 = document.getElementById('radioNo');
            const socialNameDiv = document.getElementById('socialName');
            const authorizationDiv = document.getElementById('authorizationDiv');

            function toggleSocialNameField() {
                if (socialNameOption1.checked) {
                    socialNameDiv.classList.remove('d-none');
                    authorizationDiv.classList.remove('d-none');
                } else {
                    socialNameDiv.classList.add('d-none');
                    authorizationDiv.classList.add('d-none');
                }
            }

            socialNameOption1.addEventListener('change', toggleSocialNameField);
            socialNameOption2.addEventListener('change', toggleSocialNameField);

            setTimeout(toggleSocialNameField, 10);
        });
    </script>
@endpush
