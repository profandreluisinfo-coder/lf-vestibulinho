 @extends('layouts.admin')

 @section('page-title', 'Candidatos por Sala')

 @push('styles')
     <style>
         .info-card {
             border: 0;
             border-left: 5px solid var(--accent);
             border-radius: .75rem;
             background-color: #fff;
             transition: transform .15s ease, box-shadow .15s ease;
         }

         .info-card:hover {
             transform: translateY(-2px);
             box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12) !important;
         }

         .info-icon {
             width: 44px;
             height: 44px;
             flex-shrink: 0;
             display: flex;
             align-items: center;
             justify-content: center;
             border-radius: 50%;
             font-size: 1.25rem;
             color: var(--accent);
             background-color: color-mix(in srgb, var(--accent) 15%, white);
         }

         .info-box {
             background-color: #f8f9fa;
             border-radius: .5rem;
             padding: .6rem .9rem;
         }

         .info-box small {
             display: block;
             text-transform: uppercase;
             letter-spacing: .04em;
             font-size: .7rem;
             color: #6c757d;
         }

         .info-link {
             display: flex;
             align-items: center;
             gap: .6rem;
             padding: .55rem .8rem;
             border-radius: .5rem;
             background-color: #f8f9fa;
             color: #212529;
             text-decoration: none;
             font-size: .95rem;
             transition: background-color .15s ease;
         }

         .info-link:hover {
             background-color: color-mix(in srgb, var(--accent) 15%, white);
             color: #212529;
         }

         .info-link i {
             color: var(--accent);
         }
     </style>
 @endpush

 @section('content')
     @php
         $show = \App\Models\Exam::exists();
     @endphp

     <div class="container-fluid">

         @if (!$process)
             <div class="alert alert-info">Nenhum processo seletivo está cadastrado no momento.</div>
         @else
             @if (session('success'))
                 <div class="alert alert-success alert-dismissible fade show" role="alert">
                     {{ session('success') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>
             @endif

             @if (session('error'))
                 <div class="alert alert-danger alert-dismissible fade show" role="alert">
                     {{ session('error') }}
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                 </div>
             @endif

             @if ($show)
                 <div class="row g-4 mb-4">

                     <div class="row g-4 mb-4">

                         {{-- Card: Informações da Prova --}}
                         <div class="col-12 col-md-4">
                             <div class="card info-card shadow-sm h-100" style="--accent: #0d6efd;">
                                 <div class="card-body">

                                     <div class="d-flex align-items-center gap-3 mb-3">
                                         <div class="info-icon">
                                             <i class="bi bi-calendar-event"></i>
                                         </div>
                                         <h5 class="mb-0">Informações da Prova</h5>
                                     </div>

                                     <div class="d-flex flex-column gap-2">
                                         <div class="info-box">
                                             <small><i class="bi bi-calendar3 me-1"></i>Data</small>
                                             <strong>{{ $process?->exam_date?->format('d/m/Y') ?? '—' }}</strong>
                                         </div>

                                         <div class="info-box">
                                             <small><i class="bi bi-clock me-1"></i>Horário</small>
                                             <strong>{{ $process?->exam_time?->format('H:i') ?? '—' }}</strong>
                                         </div>
                                     </div>

                                 </div>
                             </div>
                         </div>

                         {{-- Card: Listas --}}
                         <div class="col-12 col-md-4">
                             <div class="card info-card shadow-sm h-100" style="--accent: #6f42c1;">
                                 <div class="card-body">

                                     <div class="d-flex align-items-center gap-3 mb-3">
                                         <div class="info-icon">
                                             <i class="bi bi-file-earmark-text"></i>
                                         </div>
                                         <h5 class="mb-0">Listas</h5>
                                     </div>

                                     <div class="d-flex flex-column gap-2">
                                         <a href="{{ route('admin.allocations.lists.pdf', ['type' => 'geral']) }}"
                                             target="_blank" class="info-link">
                                             <i class="bi bi-printer"></i>Geral (Consulta)
                                         </a>

                                         <a href="{{ route('admin.allocations.lists.pdf', ['type' => 'salas']) }}"
                                             target="_blank" class="info-link">
                                             <i class="bi bi-printer"></i>Mural e Salas
                                         </a>

                                         <a href="{{ route('admin.allocations.lists.pdf', ['type' => 'assinaturas']) }}"
                                             target="_blank" class="info-link">
                                             <i class="bi bi-printer"></i>Folhas de Assinatura
                                         </a>
                                     </div>

                                 </div>
                             </div>
                         </div>

                         {{-- Card: Acesso aos Locais de Prova --}}
                         <div class="col-12 col-md-4">
                             <div class="card info-card shadow-sm h-100"
                                 style="--accent: {{ $locationReleased ? '#198754' : '#dc3545' }};">
                                 <div class="card-body">

                                     <div class="d-flex align-items-center gap-3 mb-3">
                                         <div class="info-icon">
                                             <i class="bi bi-geo-alt"></i>
                                         </div>
                                         <h5 class="mb-0">Acesso aos Locais</h5>
                                     </div>

                                     <div class="info-box text-center py-3">
                                         <i class="bi {{ $locationReleased ? 'bi-unlock-fill text-success' : 'bi-lock-fill text-danger' }}"
                                             style="font-size: 2rem;"></i>

                                         <div class="mt-2">
                                             <span class="badge bg-{{ $locationReleased ? 'success' : 'danger' }}">
                                                 {{ $locationReleased ? 'Liberado' : 'Bloqueado' }}
                                             </span>
                                         </div>

                                         @unless ($locationReleased)
                                             <p class="text-muted small mt-2 mb-0">
                                                 O acesso aos locais de prova ainda não foi liberado.
                                             </p>
                                         @endunless
                                     </div>

                                 </div>
                             </div>
                         </div>

                     </div>
                 </div>

                 {{-- Cabeçalho --}}
                 <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                     <div>
                         <h5 class="mb-1">
                             <i class="bi bi-card-list me-2 text-primary"></i>
                             Candidatos por Sala
                         </h5>
                         <p class="text-muted small mb-0">
                             Relação de candidatos distribuídos por local e sala de prova.
                         </p>
                         @if ($candidates->isNotEmpty())
                             <div class="mt-2">
                                 <span class="badge bg-{{ $isFinalized ? 'success' : 'warning text-dark' }}">
                                     <i class="bi {{ $isFinalized ? 'bi-check-circle' : 'bi-pencil-square' }} me-1"></i>
                                     {{ $isFinalized ? 'Finalizada' : 'Rascunho' }}
                                 </span>
                                 <small class="text-muted ms-1">
                                     Status administrativo da alocação.
                                 </small>
                             </div>
                         @endif
                     </div>

                     <div class="d-flex flex-wrap gap-2">
                         @if ($candidates->isNotEmpty() && !$isFinalized)
                             <form id="finalize-exam-form" method="POST" action="{{ route('admin.exam.finalize') }}">
                                 @csrf
                                 @method('PATCH')
                                 <button type="submit" class="btn btn-success btn-sm">
                                     <i class="bi bi-check-circle me-2"></i>Finalizar alocação
                                 </button>
                             </form>
                         @endif
                         @if ($candidates->isNotEmpty() && !($process?->isLocationReleased() ?? false))
                             <form id="delete-exam-allocation-form" method="POST"
                                 action="{{ route('admin.exam.destroy') }}">
                                 @csrf
                                 @method('DELETE')
                                 <button type="submit" class="btn btn-outline-danger btn-sm">
                                     <i class="bi bi-trash me-2"></i>
                                     Apagar alocação
                                 </button>
                             </form>
                         @endif
                     </div>
                 </div>
             @endif

             @forelse ($candidates as $location => $rooms)
                 {{-- Local de prova --}}
                 <div class="card border-0 shadow-sm mb-4">

                     <div class="card-header bg-white py-3">
                         <div class="d-flex align-items-center gap-2">
                             <i class="bi bi-geo-alt text-primary fs-5"></i>
                             <div>
                                 <h6 class="mb-0">{{ $location }}</h6>
                                 <small class="text-muted">
                                     {{ $rooms->count() }}
                                     {{ $rooms->count() === 1 ? 'sala' : 'salas' }}
                                 </small>
                             </div>
                         </div>
                     </div>

                     <div class="card-body p-3">

                         <div class="row g-3">

                             @foreach ($rooms as $room => $roomCandidates)
                                 {{-- Sala --}}
                                 <div class="col-12 col-xl-6">

                                     <div class="card h-100 border">

                                         <div
                                             class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                             <div>
                                                 <i class="bi bi-door-open me-2 text-secondary"></i>
                                                 <strong>Sala {{ $room }}</strong>
                                             </div>

                                             <span class="badge bg-primary rounded-pill">
                                                 {{ $roomCandidates->count() }}
                                                 {{ $roomCandidates->count() === 1 ? 'candidato' : 'candidatos' }}
                                             </span>
                                         </div>

                                         <div class="table-responsive">
                                             <table class="table table-hover table-sm align-middle mb-0">

                                                 <thead class="table-light">
                                                     <tr>
                                                         <th class="text-center" style="width: 110px;">
                                                             Inscrição
                                                         </th>
                                                         <th>Nome do candidato</th>
                                                     </tr>
                                                 </thead>

                                                 <tbody>
                                                     @foreach ($roomCandidates as $candidate)
                                                         <tr>
                                                             <td class="text-center text-muted">
                                                                 {{ $candidate->inscription_id }}
                                                             </td>

                                                             <td class="text-break">
                                                                 {{ $candidate->social_name ?? $candidate->name }}
                                                                 @if ($candidate->pne)
                                                                     <i class="bi bi-person-wheelchair text-primary"
                                                                         title="Pessoa com deficiência"
                                                                         aria-hidden="true"></i>
                                                                     <span class="visually-hidden">Pessoa com
                                                                         deficiência</span>
                                                                 @endif
                                                             </td>
                                                         </tr>
                                                     @endforeach
                                                 </tbody>

                                             </table>
                                         </div>

                                     </div>

                                 </div>
                             @endforeach

                         </div>

                     </div>
                 </div>

             @empty

                 <div class="card border-0 shadow-sm">
                     <div class="card-body text-center py-5">
                         <i class="bi bi-clipboard-x fs-1 text-muted"></i>

                         <h6 class="mt-3">Nenhum candidato alocado</h6>

                         <p class="text-muted mb-3">
                             Ainda não existem candidatos distribuídos por sala de prova.
                         </p>

                         <a href="{{ route('admin.exam.create') }}" class="btn btn-primary btn-sm">
                             <i class="bi bi-calendar-check me-1"></i>
                             Alocar Candidatos
                         </a>
                     </div>
                 </div>
             @endforelse
         @endif
     </div>
 @endsection

 @push('scripts')
     <script>
         document.getElementById('finalize-exam-form')?.addEventListener('submit', function(event) {
             event.preventDefault();
             const form = event.currentTarget;

             Swal.fire({
                 title: 'Finalizar alocação?',
                 text: 'Isso marcará a alocação como finalizada.',
                 icon: 'question',
                 showCancelButton: true,
                 confirmButtonColor: '#198754',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Sim, finalizar',
                 cancelButtonText: 'Cancelar',
             }).then((result) => {
                 if (result.isConfirmed) {
                     form.submit();
                 }
             });
         });

         document.getElementById('delete-exam-allocation-form')?.addEventListener('submit', function(event) {
             event.preventDefault();
             const form = event.currentTarget;

             Swal.fire({
                 title: 'Apagar alocação?',
                 text: 'Esta ação não pode ser desfeita. Todos os candidatos serão removidos das salas de prova.',
                 icon: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#dc3545',
                 cancelButtonColor: '#6c757d',
                 confirmButtonText: 'Sim, apagar',
                 cancelButtonText: 'Cancelar',
             }).then((result) => {
                 if (result.isConfirmed) {
                     form.submit();
                 }
             });
         });
     </script>
 @endpush
