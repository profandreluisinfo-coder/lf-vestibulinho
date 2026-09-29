@extends('layouts.admin')

@section('page-title', 'Relatório - Candidatos com Nome Social')

@section('content')

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-universal-access me-1"></i>
                <h6 class="mb-0 text-muted fw-normal">Relatórios Personalizados</h6>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.reports.general') }}" class="row g-3 mb-4">

            <div class="col-md-3">
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

            <div class="col-md-3">
                <label class="form-label">Agrupar por (só no Resumo)</label>
                <select name="group_by" class="form-select">
                    <option value="course" @selected(request('group_by', 'course') == 'course')>
                        Curso
                    </option>
                    <option value="gender" @selected(request('group_by') == 'gender')>
                        Gênero
                    </option>
                    <option value="school" @selected(request('group_by') == 'school')>Escola</option>
                    <option value="process" @selected(request('group_by') == 'process')>Processo/ano</option>
                </select>
            </div>

            <div class="col-md-3">
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

            <div class="col-md-3">
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

            <div class="col-md-3">
                <label class="form-label">Pessoa com Deficiência</label>
                <select name="pcd" class="form-select">
                    <option value="">Todos os candidatos</option>
                    <option value="all" @selected(request('pcd') == 'all')>Somente PCDs</option>
                    <option value="pending" @selected(request('pcd') == 'pending')>PCD: Pendente</option>
                    <option value="accepted" @selected(request('pcd') == 'accepted')>PCD: Aceito</option>
                    <option value="rejected" @selected(request('pcd') == 'rejected')>PCD: Rejeitado</option>
                </select>
            </div>

            <div class="col-md-3">
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
                        Nome Social: Aceito
                    </option>
                    <option value="rejected" @selected(request('social_name') == 'rejected')>
                        Nome Social: Rejeitado
                    </option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Escola (parte do nome)</label>
                <input type="text" name="school" class="form-control" placeholder="Ex.: João Silva"
                    value="{{ request('school') }}">
            </div>

            <div class="col-md-6 d-flex align-items-end gap-2">

                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-file-earmark-bar-graph me-1"></i>
                    Gerar relatório
                </button>

                <a href="{{ route('admin.reports.general.pdf', request()->query()) }}" target="_blank"
                    class="btn btn-outline-danger px-4">
                    <i class="bi bi-file-earmark-pdf me-1"></i>
                    Exportar PDF
                </a>

            </div>

        </form>

        @if ($type === 'summary')

            <table class="table">
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
        @else
            {{-- Aqui fica a sua tabela de LISTA, exatamente como já estava --}}
            <table class="table">
                <thead>
                    <tr>
                        <th>Candidato</th>
                        <th>Curso</th>
                        <th>Escola</th>
                        <th>Gênero</th>
                        <th>PCD</th>
                        <th>Nome Social</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inscriptions as $inscription)
                        <tr>
                            <td>{{ $inscription->user->name }}</td>
                            <td>{{ $inscription->course->name }}</td>
                            <td>{{ $inscription->user->academic?->school ?? '—' }}</td>
                            <td>{{ $inscription->user->gender }}</td>
                            <td>
                                @switch($inscription->user->pne?->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pendente
                                        </span>
                                    @break

                                    @case('accepted')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Aceito
                                        </span>
                                    @break

                                    @case('rejected')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                            Rejeitado
                                        </span>
                                    @break

                                    @default
                                        —
                                @endswitch
                            </td>
                            <td>
                                @switch($inscription->user->lgbt?->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-hourglass-split me-1"></i>
                                            Pendente
                                        </span>
                                    @break

                                    @case('accepted')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle-fill me-1"></i>
                                            Aceito
                                        </span>
                                    @break

                                    @case('rejected')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle-fill me-1"></i>
                                            Rejeitado
                                        </span>
                                    @break

                                    @default
                                        —
                                @endswitch
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6">Nenhum candidato encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $inscriptions->links() }}

            @endif
        </div>

    @endsection
