@extends('layouts.admin.master')

@section('page-title', 'Ficha de Inscrição')

@section('dash-content')
<div class="container">
    <div class="card shadow-lg border-0 mb-4">
        <div class="card-header">
            <i class="bi bi-person-vcard animate__animated animate__fadeIn me-2"></i> DETALHES DA INSCRIÇÃO
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-2 g-4">

                {{-- Dados da Inscrição --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">📄 Dados da Inscrição</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Inscrição Nº:</strong> {{ $user->inscription->id }}</li>
                            <li class="list-group-item"><strong>Curso Pretendido:</strong> {{ $user->inscription->course->description }}</li>
                            <li class="list-group-item"><strong>Data da Inscrição:</strong> {{ \Carbon\Carbon::parse($user->inscription->created_at)->format('d/m/Y') }}</li>
                        </ul>
                    </fieldset>
                </div>

                {{-- Identificação do Candidato --}}
                <div class="col bg-warning">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">🧑‍💼 Identificação do Candidato</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>CPF:</strong> {{ $user->cpf }}</li>
                            <li class="list-group-item"><strong>Nome de Registro:</strong> {{ $user->name }}</li>
                            @if ($user->social_name)
                            <li class="list-group-item"><strong>Nome Social/Afetivo:</strong> {{ $user->social_name }}</li>
                            @endif
                            <li class="list-group-item"><strong>Gênero:</strong> {{ $user->gender }}</li>
                            <li class="list-group-item">
                                <strong>E-mail:</strong> {{ $user->email }}
                                <strong class="ms-3">Telefone:</strong> {{ $user->user_detail->phone }}
                            </li>
                        </ul>
                    </fieldset>
                </div>

                {{-- Documentos Pessoais --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">📑 Documentos Pessoais</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Nacionalidade:</strong> {{ $user->user_detail->nationality }}</li>
                            <li class="list-group-item">
                                <strong>Tipo:</strong> {{ $user->user_detail->doc_type }}
                                <strong class="ms-3">Número:</strong> {{ $user->user_detail->doc_number }}
                            </li>
                            <li class="list-group-item"><strong>Data de Nascimento:</strong> {{ $user->birth }}</li>

                        </ul>
                    </fieldset>
                </div>

                {{-- Certidão de Nascimento --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">📝 Certidão de Nascimento</legend>
                        <ul class="list-group list-group-flush">
                            @if (!empty($user->user_detail->new_number))
                                <li class="list-group-item"><strong>Nº Certidão:</strong> {{ $user->user_detail->new_number }}</li>
                            @else
                                <li class="list-group-item">
                                    <strong>Folhas:</strong> {{ $user->user_detail->fls }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Livro:</strong> {{ $user->user_detail->book }}
                                <li class="list-group-item">
                                    <strong>Nº Certidão:</strong> {{ $user->user_detail->old_number }}
                                    <strong class="ms-3">Município:</strong> {{ $user->user_detail->municipality }}
                                </li>
                            @endif
                        </ul>
                    </fieldset>
                </div>

                {{-- Filiação --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">👨‍👩‍👧‍👦 Filiação / Responsável Legal</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Mãe:</strong> {{ $user->user_detail->mother }}
                                @if ($user->user_detail->mother_phone)
                                    <strong class="ms-3">Telefone:</strong> {{ $user->user_detail->mother_phone }}
                                @endif
                            </li>
                            @if ($user->user_detail->father)
                                <li class="list-group-item">
                                    <strong>Pai:</strong> {{ $user->user_detail->father }}
                                    @if ($user->user_detail->father_phone)
                                        <strong class="ms-3">Telefone:</strong> {{ $user->user_detail->father_phone }}
                                    @endif
                                </li>
                            @endif
                            @if ($user->user_detail->responsible)
                                <li class="list-group-item">
                                    <strong>Responsável Legal:</strong> {{ $user->user_detail->responsible }}
                                </li>
                                <li class="list-group-item">
                                    <strong>Parentesco:</strong> {{ $user->user_detail->degree }}
                                    @if ($user->user_detail->kinship)
                                        <strong class="ms-3">Descrição:</strong> {{ $user->user_detail->kinship }}
                                    @endif
                                    <strong class="ms-3">Telefone:</strong> {{ $user->user_detail->responsible_phone }}
                                </li>
                            @endif
                            <li class="list-group-item"><strong>E-mail:</strong> {{ $user->user_detail->parents_email }}</li>
                        </ul>
                    </fieldset>
                </div>

                {{-- Escolaridade --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">🎓 Escolaridade</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Escola:</strong> {{ $user->user_detail->school_name }}</li>
                            <li class="list-group-item"><strong>RA:</strong> {{ $user->user_detail->school_ra }}</li>
                            <li class="list-group-item">
                                <strong>Cidade:</strong> {{ $user->user_detail->school_city }}
                                <strong class="ms-3">Estado:</strong> {{ $user->user_detail->school_state }}
                            </li>
                            <li class="list-group-item"><strong>Ano de Conclusão:</strong> {{ $user->user_detail->school_year }}</li>
                        </ul>
                    </fieldset>
                </div>

                {{-- Endereço --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">🏠 Endereço</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>CEP:</strong> {{ $user->user_detail->zip }}</li>
                            <li class="list-group-item">
                                <strong>Rua:</strong> {{ $user->user_detail->street }}
                                <strong class="ms-3">Nº:</strong> {{ $user->user_detail->number }}
                                @if ($user->user_detail->complement)
                                    <strong class="ms-3">Complemento:</strong> {{ $user->user_detail->complement }}
                                @endif
                            </li>
                            <li class="list-group-item"><strong>Bairro:</strong> {{ $user->user_detail->burgh }}</li>
                            <li class="list-group-item">
                                <strong>Cidade:</strong> {{ $user->user_detail->city }}
                                <strong class="ms-3">Estado:</strong> {{ $user->user_detail->state }}
                            </li>
                        </ul>
                    </fieldset>
                </div>

                {{-- Educação Especial --}}
                <div class="col @if ($user->user_detail?->accessibility) bg-info @endif">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">♿ Educação Especial</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Elegível:</strong>
                                {{ $user->user_detail?->accessibility ? 'SIM' : 'NÃO' }}
                            </li>
                            @if ($user->user_detail?->accessibility)
                                <li class="list-group-item">
                                    <strong>Necessidade Especial:</strong> {{ $user->user_detail->accessibility }}
                                </li>
                            @endif
                        </ul>
                    </fieldset>
                </div>

                {{-- Programas Sociais --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">🤝 Programas Sociais</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Beneficiário Bolsa-Família:</strong>
                                {{ $user->user_detail?->nis ? 'SIM' : 'NÃO' }}
                            </li>
                            @if ($user->user_detail?->nis)
                                <li class="list-group-item">
                                    <strong>NIS:</strong> {{ $user->user_detail->nis }}
                                </li>
                            @endif
                        </ul>
                    </fieldset>
                </div>

                {{-- Outras Informações --}}
                <div class="col">
                    <fieldset class="h-100 border rounded p-3 bg-light-subtle shadow">
                        <legend class="fw-semibold">💡 Outras Informações</legend>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Problema de Saúde ou Alergia:</strong>
                                {{ $user->user_detail->health ? 'SIM' : 'NÃO' }}
                            </li>
                            @if ($user->user_detail?->health)
                                <li class="list-group-item">
                                    <strong>Descrição:</strong> {{ $user->user_detail->health }}
                                </li>
                            @endif
                        </ul>
                    </fieldset>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection