@extends('layouts.admin')

@section('page-title', 'Relatório Geral')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-clipboard-data me-1"></i>
                <h6 class="mb-0 text-muted fw-normal">Relatórios Personalizados</h6>
            </div>
        </div>

        <div class="alert alert-light border mb-4">
            <h6 class="fw-bold mb-2">
                <i class="bi bi-info-circle me-1"></i> Como usar esta tela
            </h6>

            <p class="mb-2">
                Aqui você monta relatórios sobre os candidatos do vestibulinho escolhendo os filtros
                que quiser. Depois é só clicar em <strong>Gerar relatório</strong> ou em
                <strong>Exportar PDF</strong>.
            </p>

            <ul class="mb-2">
                <li>
                    <strong>Lista de candidatos:</strong> mostra um candidato por linha
                    (nome, curso, escola, gênero, PCD e Nome Social).
                </li>
                <li>
                    <strong>Resumo (contagem):</strong> mostra números. Use
                    <em>"Agrupar por"</em> para escolher como contar (por curso, gênero, escola...).
                </li>
                <li>
                    Os filtros podem ser <strong>combinados</strong>. Quanto mais você escolher,
                    mais específico fica o resultado.
                </li>
            </ul>

            <details>
                <summary class="fw-semibold" style="cursor: pointer;">Ver exemplos (clique para testar)</summary>

                <ul class="mt-2 mb-0">
                    <li>
                        <a href="{{ route('admin.reports.general', ['type' => 'summary', 'group_by' => 'course']) }}">
                            Quantos candidatos há em cada curso?
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('admin.reports.general', ['type' => 'summary', 'group_by' => 'course_gender']) }}">
                            Quantos homens e mulheres há em cada curso?
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.general', ['pcd' => 'pending']) }}">
                            Quais candidatos PCD estão com a análise pendente?
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.general', ['social_name' => 'accepted']) }}">
                            Quais candidatos tiveram o Nome Social aceito?
                        </a>
                    </li>
                    <li>
                        Quais candidatos vêm de uma escola específica? Digite parte do nome no campo
                        <strong>Escola</strong> e clique em <strong>Gerar relatório</strong>.
                    </li>
                </ul>
            </details>
        </div>

        <form method="GET" action="{{ route('admin.reports.general') }}" class="row g-3 mb-4">

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Tipo de relatório</label>
                <select name="type" class="form-select">
                    <option value="list" @selected(request('type', 'list') == 'list')>
                        Lista de candidatos
                    </option>
                    <option value="summary" @selected(request('type') == 'summary')>
                        Resumo (contagem)
                    </option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Agrupar por (só no Resumo)</label>
                <select name="group_by" class="form-select">
                    <option value="course" @selected(request('group_by', 'course') == 'course')>
                        Curso
                    </option>
                    <option value="gender" @selected(request('group_by') == 'gender')>
                        Gênero
                    </option>
                    <option value="school" @selected(request('group_by') == 'school')>Escola</option>
                    {{-- <option value="process" @selected(request('group_by') == 'process')>Processo/ano</option> --}}
                    <option value="course_gender" @selected(request('group_by') == 'course_gender')>Curso e Gênero</option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Curso</label>
                <select name="course_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @selected(request('course_id') == $course->id)>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Gênero</label>
                <select name="gender" class="form-select">
                    <option value="">Todos</option>
                    @foreach (\App\Models\User::GENDERS as $value => $label)
                        <option value="{{ $value }}" @selected(request('gender') == $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Pessoa com Deficiência</label>
                <select name="pcd" class="form-select">
                    <option value="">Todos os candidatos</option>
                    <option value="all" @selected(request('pcd') == 'all')>Somente PCDs</option>
                    <option value="pending" @selected(request('pcd') == 'pending')>PCD: Pendente</option>
                    <option value="accepted" @selected(request('pcd') == 'accepted')>PCD: Deferido</option>
                    <option value="rejected" @selected(request('pcd') == 'rejected')>PCD: Indeferido</option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Nome Social</label>
                <select name="social_name" class="form-select">
                    <option value="">Todos os candidatos</option>
                    <option value="all" @selected(request('social_name') == 'all')>
                        Somente com Nome Social
                    </option>
                    <option value="pending" @selected(request('social_name') == 'pending')>
                        Nome Social: Pendente
                    </option>
                    <option value="accepted" @selected(request('social_name') == 'accepted')>
                        Nome Social: Deferido
                    </option>
                    <option value="rejected" @selected(request('social_name') == 'rejected')>
                        Nome Social: Indeferido
                    </option>
                </select>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Escola (parte do nome)</label>
                <input type="text" name="school" class="form-control" placeholder="Ex.: João Silva"
                    value="{{ request('school') }}">
            </div>

            {{-- <div class="col-12 col-sm-6 col-md-3">
                <label class="form-label">Processo/ano</label>
                <select name="process_id" class="form-select">
                    <option value="">Todos</option>
                    @foreach ($processes as $proc)
                        <option value="{{ $proc->id }}" @selected(request('process_id') == $proc->id)>
                            {{ $proc->year }}
                        </option>
                    @endforeach
                </select>
            </div> --}}

            <div class="col-12 col-md-6 d-flex flex-wrap align-items-end gap-2">

                <button type="submit" class="btn btn-primary px-4 flex-fill flex-md-grow-0">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>
                    Gerar relatório
                </button>

                <a href="{{ route('admin.reports.general.pdf', request()->query()) }}" target="_blank"
                    class="btn btn-outline-danger px-4 flex-fill flex-md-grow-0">
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Exportar PDF
                </a>

            </div>

        </form>

        @if ($type === 'summary')

            @if ($groupBy === 'course_gender')

                {{-- RESUMO: Curso e Gênero --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Curso</th>
                                <th>Masculino</th>
                                <th>Feminino</th>
                                <th>Outro</th>
                                <th>Prefiro não informar</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summary as $row)
                                <tr>
                                    <td>{{ $row->grupo }}</td>
                                    <td>{{ $row->masculino }}</td>
                                    <td>{{ $row->feminino }}</td>
                                    <td>{{ $row->outro }}</td>
                                    <td>{{ $row->nao_informado }}</td>
                                    <td>{{ $row->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Nenhum resultado encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total geral</th>
                                <th>{{ $summary->sum('masculino') }}</th>
                                <th>{{ $summary->sum('feminino') }}</th>
                                <th>{{ $summary->sum('outro') }}</th>
                                <th>{{ $summary->sum('nao_informado') }}</th>
                                <th>{{ $summary->sum('total') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                {{-- RESUMO: simples (Grupo / Total) --}}
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Grupo</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($summary as $row)
                                <tr>
                                    <td>{{ $row->grupo }}</td>
                                    <td>{{ $row->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">Nenhum resultado encontrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total geral</th>
                                <th>{{ $summary->sum('total') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        @else
            {{-- LISTA de candidatos --}}
            <div class="table-responsive border rounded">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap">Inscrição</th>
                            <th style="min-width: 180px;">Candidato</th>
                            <th class="text-nowrap">Gênero</th>
                            <th style="min-width: 140px;">Nome Social</th>
                            <th class="text-nowrap">Situação</th>
                            <th style="min-width: 160px;">Curso</th>
                            <th style="min-width: 200px;">Escola</th>
                            <th class="text-nowrap">PCD</th>
                            <th style="min-width: 160px;">Condição</th>
                            <th style="min-width: 160px;">Requer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inscriptions as $inscription)
                            <tr>
                                <td class="text-nowrap">{{ $inscription->id }}</td>
                                <td>{{ $inscription->user->name }}</td>
                                <td class="text-nowrap">{{ $inscription->user->gender }}</td>
                                <td>{{ $inscription->user->lgbt?->name ?? '—' }}</td>
                                <td class="text-nowrap">
                                    @include('partials.reports.status-badge', [
                                        'status' => $inscription->user->lgbt?->status,
                                    ])
                                </td>
                                <td>{{ $inscription->course->name }}</td>
                                <td>{{ $inscription->user->academic?->school ?? '—' }}</td>
                                <td class="text-nowrap">
                                    @include('partials.reports.status-badge', [
                                        'status' => $inscription->user->pne?->status,
                                    ])
                                </td>
                                <td>{{ $inscription->user->pne?->description ?? '—' }}</td>
                                <td>{{ $inscription->user->pne?->support ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    Nenhum candidato encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($inscriptions->hasPages())
                <div class="mt-3 overflow-auto">
                    {{ $inscriptions->links() }}
                </div>
            @endif

        @endif

    </div> {{-- container --}}

@endsection
