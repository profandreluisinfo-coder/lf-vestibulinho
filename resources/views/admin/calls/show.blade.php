@extends('layouts.admin')

@section('page-title', 'Gerenciar chamada ' . $callList->number)

@section('content')
    <div class="container my-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
            <div>
                <h3 class="mb-1">Chamada nº {{ $callList->number }}</h3>
                <div class="text-muted">
                    {{ $callList->date->format('d/m/Y') }} às {{ $callList->time->format('H:i') }}
                    · {{ $callList->status === 'completed' ? 'Publicada' : 'Rascunho' }}
                </div>
            </div>
            <a href="{{ route('admin.calls.index') }}" class="btn btn-outline-secondary">Voltar às chamadas</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Classificação</th>
                        <th>Inscrição</th>
                        <th>Candidato</th>
                        <th>PcD</th>
                        <th>Situação da matrícula</th>
                        <th>Curso</th>
                        @if ($callList->status === 'completed')<th>Registrar resultado</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($callList->calls->sortBy(fn ($call) => $call->result?->ranking) as $call)
                        @php($user = $call->result?->inscription?->user)
                        <tr>
                            <td>{{ $call->result?->ranking ?? '—' }}</td>
                            <td>{{ $call->result?->inscription_id ?? '—' }}</td>
                            <td>{{ $user?->lgbt?->status === 'accepted' ? $user->lgbt->name : $user?->name }}</td>
                            <td>{{ $call->is_pcd_reserve ? 'Sim (reserva)' : ($user?->pne?->status === 'accepted' ? 'Sim' : 'Não') }}</td>
                            <td>{{ match ($call->enrollment_status) {
                                'awaiting' => 'Aguardando atendimento',
                                'enrolled' => 'Matriculado',
                                'documents_pending' => 'Documentos pendentes',
                                'lost' => 'Perdeu a vaga',
                                'withdrawn' => 'Desistiu após matrícula',
                                'waiting_course' => 'Aguardando vaga no curso',
                                default => $call->enrollment_status,
                            } }}</td>
                            <td>{{ $call->course?->name ?? '—' }}</td>
                            @if ($callList->status === 'completed')
                                <td style="min-width: 340px">
                                    <form method="POST" action="{{ route('admin.calls.enrollment.update', $call) }}" class="d-grid gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="enrollment_status" class="form-select form-select-sm" required>
                                            @foreach (['enrolled' => 'Matriculado', 'documents_pending' => 'Documentos pendentes', 'lost' => 'Perdeu a vaga', 'withdrawn' => 'Desistiu após matrícula', 'waiting_course' => 'Aguardando vaga no curso'] as $value => $label)
                                                <option value="{{ $value }}" @selected($call->enrollment_status === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <select name="course_id" class="form-select form-select-sm">
                                            <option value="">Selecione o curso quando aplicável</option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}" @selected($call->course_id === $course->id)>
                                                    {{ $course->name }} ({{ $course->pivot->vacancies }} vagas)
                                                </option>
                                            @endforeach
                                        </select>
                                        <label class="small mb-0">Prazo de regularização documental (mínimo de 48 horas)</label>
                                        <input type="datetime-local" name="regularization_deadline" class="form-control form-control-sm"
                                            value="{{ $call->regularization_deadline?->format('Y-m-d\\TH:i') }}">
                                        <textarea name="enrollment_notes" class="form-control form-control-sm" rows="2" maxlength="2000" placeholder="Observação interna">{{ $call->enrollment_notes }}</textarea>
                                        <button class="btn btn-sm btn-primary" type="submit">Salvar resultado</button>
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Esta chamada ainda não tem candidatos.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
