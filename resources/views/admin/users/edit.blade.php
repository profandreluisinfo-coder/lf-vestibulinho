@extends('layouts.admin')

@section('page-title', 'Editar Candidato')

{{-- Reaproveita o mesmo CSS da tela de detalhes (fi-card, fi-header...) --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/inscriptions/show.css') }}">
@endpush

@php
    /*
     * ATENÇÃO: ajuste os TEXTOS destas listas conforme o seu sistema.
     * Os VALORES (1, 2, 3...) precisam ser iguais aos que você já grava no banco.
     */
    $nationalities = [
        '1' => 'Brasileira',
        '2' => 'Brasileira naturalizada',
        '3' => 'Estrangeira',
        '4' => 'Outra',
    ];

    $documentTypes = [
        '1' => 'RG',
        '2' => 'CIN',
        '3' => 'CNH',
        '4' => 'Passaporte',
        '5' => 'RNE',
    ];

    $certificateTypes = [
        '1' => 'Nova (modelo novo)',
        '2' => 'Antiga',
    ];

    $statuses = [
        'pending' => 'Pendente',
        'accepted' => 'Aceito',
        'rejected' => 'Recusado',
    ];

    // Grau de parentesco vem da tabela "degrees" (o valor 8 = "Outro")
    $degrees = \App\Models\Degree::orderBy('id')->pluck('description', 'id');

    // Lista de escolas para o campo "academic[school]"
    $schools = \App\Models\School::orderBy('name')->get();
@endphp

@section('content')
    <div class="container">

        {{-- Header --}}
        <div class="fi-header">
            <div>
                <p class="fi-header-title">Editar Candidato</p>
                <p class="fi-header-sub">Processo Seletivo {{ $process?->year }} — {{ $user->name }}</p>
            </div>
        </div>

        {{-- Resumo de erros --}}
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Alguns campos precisam de atenção. Veja os avisos em vermelho abaixo.
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.update', $user) }}" novalidate>
            @csrf
            @method('PUT')

            {{-- ===================== IDENTIFICAÇÃO ===================== --}}
            <p class="fi-section-label">Dados Pessoais</p>
            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">🧑‍💼</div>
                    <p class="fi-card-title">Identificação</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'cpf', 'label' => 'CPF', 'value' => $user->cpf, 'inputmode' => 'numeric'])
                    @include('admin.partials.field', ['name' => 'name', 'label' => 'Nome', 'value' => $user->name, 'max' => 100])
                    @include('admin.partials.field', ['name' => 'birth', 'label' => 'Data de nascimento', 'type' => 'date', 'value' => $user->getRawOriginal('birth'), 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'gender', 'label' => 'Gênero', 'value' => $user->getRawOriginal('gender'), 'options' => \App\Models\User::GENDERS, 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'nationality', 'label' => 'Nacionalidade', 'value' => $user->getRawOriginal('nationality'), 'options' => $nationalities, 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'email', 'label' => 'E-mail', 'type' => 'email', 'value' => $user->email, 'required' => true, 'max' => 150])
                    @include('admin.partials.field', ['name' => 'phone', 'label' => 'Telefone', 'value' => $user->phone, 'class' => 'phone-mask', 'col' => 'col-md-6'])
                </div>
            </div>

            {{-- ===================== NOME SOCIAL (só se o candidato pediu) ===================== --}}
            @if ($user->lgbt)
                <div class="fi-card mb-4">
                    <div class="fi-card-header">
                        <div class="fi-card-icon">🏳️‍🌈</div>
                        <p class="fi-card-title">Nome Social</p>
                    </div>
                    <div class="row g-3 p-3">
                        @include('admin.partials.field', ['name' => 'lgbt[name]', 'label' => 'Nome social', 'value' => $user->lgbt->name, 'max' => 100, 'col' => 'col-md-6'])
                        @include('admin.partials.field', ['name' => 'lgbt[status]', 'label' => 'Situação', 'value' => $user->lgbt->status, 'options' => $statuses, 'col' => 'col-md-6'])
                        @include('admin.partials.field', ['name' => 'lgbt[observations]', 'label' => 'Observações', 'value' => $user->lgbt->observations, 'max' => 255, 'col' => 'col-12'])
                    </div>
                </div>
            @endif

            {{-- ===================== DOCUMENTO ===================== --}}
            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">📑</div>
                    <p class="fi-card-title">Documento Pessoal</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'document[type]', 'label' => 'Tipo', 'value' => $user->document?->getRawOriginal('type'), 'options' => \App\Models\Document::TYPES, 'required' => true, 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'document[number]', 'label' => 'Número', 'value' => $user->document?->number, 'required' => true, 'max' => 15, 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'document[expedition]', 'label' => 'Data de expedição', 'type' => 'date', 'value' => $user->document?->getRawOriginal('expedition'), 'col' => 'col-md-4'])
                </div>
            </div>

            {{-- ===================== CERTIDÃO ===================== --}}
            <p class="fi-section-label">Certidão de Nascimento</p>
            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">📝</div>
                    <p class="fi-card-title">Certidão</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'certificate[type]', 'label' => 'Modelo', 'value' => $user->certificate?->type, 'options' => $certificateTypes, 'required' => true, 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'certificate[number]', 'label' => 'Número', 'value' => $user->certificate?->number, 'required' => true, 'max' => 32, 'col' => 'col-md-8'])

                    {{-- Só aparece para certidão ANTIGA (tipo 2) --}}
                    <div class="col-12" id="certificate-old-fields">
                        <div class="row g-3">
                            @include('admin.partials.field', ['name' => 'certificate[fls]', 'label' => 'Folhas', 'value' => $user->certificate?->fls, 'max' => 10, 'col' => 'col-md-4'])
                            @include('admin.partials.field', ['name' => 'certificate[book]', 'label' => 'Livro', 'value' => $user->certificate?->book, 'max' => 10, 'col' => 'col-md-4'])
                            @include('admin.partials.field', ['name' => 'certificate[city]', 'label' => 'Município', 'value' => $user->certificate?->city, 'max' => 45, 'col' => 'col-md-4'])
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===================== FAMÍLIA ===================== --}}
            <p class="fi-section-label">Família e Responsável</p>
            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">👨‍👩‍👧‍👦</div>
                    <p class="fi-card-title">Filiação / Responsável Legal</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'mother[name]', 'label' => 'Nome da mãe', 'value' => $user->mother?->name, 'max' => 255])
                    @include('admin.partials.field', ['name' => 'mother[phone]', 'label' => 'Telefone da mãe', 'value' => $user->mother?->phone, 'class' => 'phone-mask', 'inputmode' => 'numeric'])

                    @include('admin.partials.field', ['name' => 'father[name]', 'label' => 'Nome do pai', 'value' => $user->father?->name, 'max' => 255])
                    @include('admin.partials.field', ['name' => 'father[phone]', 'label' => 'Telefone do pai', 'value' => $user->father?->phone, 'class' => 'phone-mask', 'inputmode' => 'numeric'])

                    <div class="col-12">
                        <hr class="my-1">
                        <p class="text-muted small mb-0">
                            Pai e responsável: deixe o <strong>nome em branco</strong> para remover o registro.
                        </p>
                    </div>

                    @include('admin.partials.field', ['name' => 'guardian[name]', 'label' => 'Responsável legal', 'value' => $user->guardian?->name, 'max' => 255])
                    @include('admin.partials.field', ['name' => 'guardian[phone]', 'label' => 'Telefone do responsável', 'value' => $user->guardian?->phone, 'class' => 'phone-mask', 'inputmode' => 'numeric'])
                    @include('admin.partials.field', ['name' => 'guardian[degree_id]', 'label' => 'Grau de parentesco', 'value' => $user->guardian?->degree_id, 'options' => $degrees, 'col' => 'col-md-6'])

                    {{-- Só aparece quando o parentesco é "Outro" (8) --}}
                    <div class="col-md-6" id="guardian-kinship-wrapper">
                        <div class="row">
                            @include('admin.partials.field', ['name' => 'guardian[kinship]', 'label' => 'Qual parentesco?', 'value' => $user->guardian?->kinship, 'max' => 45, 'col' => 'col-12'])
                        </div>
                    </div>

                    @include('admin.partials.field', ['name' => 'parent_email[address]', 'label' => 'E-mail da família', 'type' => 'email', 'value' => $user->parent_email?->address, 'col' => 'col-12'])
                </div>
            </div>

            {{-- ===================== ESCOLARIDADE ===================== --}}
            <p class="fi-section-label">Formação e Endereço</p>
            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">🎓</div>
                    <p class="fi-card-title">Escolaridade</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'academic[school]', 'label' => 'Escola', 'value' => $user->academic?->school, 'required' => true, 'max' => 100, 'col' => 'col-md-8', 'list' => 'schools'])

                    <datalist id="schools">
                        @foreach ($schools as $school)
                            <option value="{{ $school->name }}">
                        @endforeach
                    </datalist>
                    @include('admin.partials.field', ['name' => 'academic[ra]', 'label' => 'RA', 'value' => $user->academic?->ra, 'max' => 20, 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'academic[city]', 'label' => 'Cidade', 'value' => $user->academic?->city, 'required' => true, 'max' => 45, 'col' => 'col-md-6'])
                    @include('admin.partials.field', ['name' => 'academic[state]', 'label' => 'UF', 'value' => $user->academic?->state, 'required' => true, 'max' => 2, 'col' => 'col-md-3'])
                    @include('admin.partials.field', ['name' => 'academic[year]', 'label' => 'Ano de conclusão', 'value' => $user->academic?->year, 'required' => true, 'max' => 4, 'inputmode' => 'numeric', 'col' => 'col-md-3'])
                </div>
            </div>

            {{-- ===================== ENDEREÇO ===================== --}}
            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">🏠</div>
                    <p class="fi-card-title">Endereço</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'zip', 'label' => 'CEP', 'value' => $user->zip, 'max' => 8, 'inputmode' => 'numeric', 'col' => 'col-md-3'])
                    @include('admin.partials.field', ['name' => 'street', 'label' => 'Rua', 'value' => $user->street, 'max' => 60, 'col' => 'col-md-6'])
                    @include('admin.partials.field', ['name' => 'home', 'label' => 'Número', 'value' => $user->home, 'max' => 10, 'col' => 'col-md-3'])
                    @include('admin.partials.field', ['name' => 'complement', 'label' => 'Complemento', 'value' => $user->complement, 'max' => 45, 'col' => 'col-md-6'])
                    @include('admin.partials.field', ['name' => 'burgh', 'label' => 'Bairro', 'value' => $user->burgh, 'max' => 45, 'col' => 'col-md-6'])
                    @include('admin.partials.field', ['name' => 'city', 'label' => 'Cidade', 'value' => $user->city, 'max' => 45, 'col' => 'col-md-8'])
                    @include('admin.partials.field', ['name' => 'state', 'label' => 'UF', 'value' => $user->state, 'max' => 45, 'col' => 'col-md-4'])
                </div>
            </div>

            {{-- ===================== COMPLEMENTARES ===================== --}}
            <p class="fi-section-label">Informações Complementares</p>

            @if ($user->pne)
                <div class="fi-card fi-card-special mb-4">
                    <div class="fi-card-header">
                        <div class="fi-card-icon">♿</div>
                        <p class="fi-card-title">Educação Especial</p>
                    </div>
                    <div class="row g-3 p-3">
                        @include('admin.partials.field', ['name' => 'pne[status]', 'label' => 'Situação', 'value' => $user->pne->status, 'options' => $statuses, 'col' => 'col-md-4'])
                        @include('admin.partials.field', ['name' => 'pne[support]', 'label' => 'Auxílio necessário', 'value' => $user->pne->support, 'max' => 255, 'col' => 'col-md-8'])

                        <div class="col-12">
                            <label for="pne_description" class="form-label">Necessidade</label>
                            <textarea id="pne_description" name="pne[description]" rows="3"
                                class="form-control @error('pne.description') is-invalid @enderror">{{ old('pne.description', $user->pne->description) }}</textarea>
                            @error('pne.description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @include('admin.partials.field', ['name' => 'pne[observations]', 'label' => 'Observações', 'value' => $user->pne->observations, 'max' => 255, 'col' => 'col-12'])
                    </div>
                </div>
            @endif

            <div class="fi-card mb-4">
                <div class="fi-card-header">
                    <div class="fi-card-icon">🤝</div>
                    <p class="fi-card-title">Programa Social e Saúde</p>
                </div>
                <div class="row g-3 p-3">
                    @include('admin.partials.field', ['name' => 'nis', 'label' => 'NIS', 'value' => $user->nis, 'class' => 'phone-mask', 'inputmode' => 'numeric', 'col' => 'col-md-4'])
                    @include('admin.partials.field', ['name' => 'health_issue', 'label' => 'Saúde e alergias', 'value' => $user->health_issue, 'max' => 100, 'col' => 'col-md-8'])
                </div>
            </div>

            {{-- ===================== BOTÕES ===================== --}}
            <div class="d-flex justify-content-end gap-2 mb-5">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-x-lg me-1"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-check-lg me-1"></i>Salvar alterações
                </button>
            </div>
        </form>
    </div>
@endsection

@push('plugins')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cleave.js/1.6.0/cleave.min.js"></script>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/admin/masks/cleave.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // Certidão antiga (2) mostra Folhas, Livro e Município
            const certType = document.getElementById('certificate_type');
            const certExtra = document.getElementById('certificate-old-fields');

            function toggleCertificate() {
                certExtra.classList.toggle('d-none', certType.value === '1');
            }
            certType.addEventListener('change', toggleCertificate);
            toggleCertificate();

            // Parentesco "Outro" (8) mostra o campo extra
            const degree = document.getElementById('guardian_degree');
            const kinship = document.getElementById('guardian-kinship-wrapper');

            function toggleKinship() {
                kinship.classList.toggle('d-none', degree.value !== '8');
            }
            degree.addEventListener('change', toggleKinship);
            toggleKinship();
        });
    </script>
@endpush