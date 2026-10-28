@extends('layouts.admin')

@section('page-title', 'Painel Administrativo')

@section('content')
    @php
        if (!$process->start || !$process->end) {
            $situation = 'Datas das inscrições ainda não definidas';
        } elseif ($process->isInscriptionOpen()) {
            $days = (int) today()->diffInDays($process->end);
            $situation =
                $days === 0 ? 'Inscrições abertas — encerram hoje!' : "Inscrições abertas — encerram em {$days} dia(s)";
        } elseif (!$process->isInscriptionStarted()) {
            $situation = 'Inscrições começam em ' . $process->formatDate($process->start);
        } elseif ($process->isInscriptionEnded()) {
            $situation = 'Inscrições encerradas';
        } else {
            $situation = 'Inscrições fechadas';
        }
    @endphp

    <div class="container">

        {{-- Cabeçalho --}}
        <div class="dash-hero mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <div class="d-flex">
                        <img src="{{ asset('assets/img/logo.webp') }}" class="me-2 mb-3" alt="Logo" height="32">
                        <h1>Vestibulinho LF {{ $process->year }}</h1>
                    </div>
                    <p class="mb-0">
                        <a href="{{ $process?->edital ? Storage::url($process->edital) : '#' }}" target="_blank">Edital<i
                                class="bi bi-box-arrow-up-right fs-6 ms-2"></i></a>
                    </p>
                </div>
                <span class="badge {{ $process->status === 'open' ? 'text-bg-success' : 'text-bg-secondary' }}">
                    {{ $process->status === 'open' ? 'Aberto' : 'Fechado' }}
                </span>
            </div>
            <hr class="border-light opacity-25">
            <p class="mb-0"><i class="bi bi-info-circle me-1"></i> {{ $situation }}</p>
        </div>

        {{-- Avisos --}}
        <div class="mb-4">
            @forelse ($alerts as $alert)
                <div class="dash-alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $alert }}</span>
                </div>
            @empty
                <div class="dash-alert ok">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Nenhum aviso de prazo por enquanto. Está tudo em dia!</span>
                </div>
            @endforelse
        </div>

        {{-- Cartões de números --}}
        {{-- Candidatos --}}
        <h2 class="dash-section-title">Candidatos</h2>
        <div class="row g-3 statistics-container">
            <div class="col-12 col-md-6">
                <a href="{{ route('admin.inscriptions.index') }}" class="card dash-card h-100">
                    <div class="dash-card-icon teal"><i class="bi bi-person-lines-fill"></i></div>
                    <div>
                        <div class="dash-card-number">{{ $totalInscriptions }}</div>
                        <p class="dash-card-label">Inscrições</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6">
                <a href="{{ route('admin.users.index') }}" class="card dash-card h-100">
                    <div class="dash-card-icon teal"><i class="bi bi-person-x"></i></div>
                    <div>
                        <div class="dash-card-number">{{ $usersWithoutInscription }}</div>
                        <p class="dash-card-label">Cadastrados sem inscrição</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Para analisar --}}
        <h2 class="dash-section-title">Para analisar</h2>
        <div class="row g-3 statistics-container">
            <div class="col-12 col-md-4">
                <a href="{{ route('admin.inscriptions.pcds') }}" class="card dash-card h-100">
                    <div class="dash-card-icon amber"><i class="bi bi-universal-access"></i></div>
                    <div>
                        <div class="dash-card-number">{{ $stats['pne_pending'] }}</div>
                        <p class="dash-card-label">PcD pendentes</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="{{ route('admin.inscriptions.lgbts') }}" class="card dash-card h-100">
                    <div class="dash-card-icon amber"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <div class="dash-card-number">{{ $stats['lgbt_pending'] }}</div>
                        <p class="dash-card-label">Nome Social pendentes</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="{{ route('admin.appeals.index') }}" class="card dash-card h-100">
                    <div class="dash-card-icon amber"><i class="bi bi-file-earmark-text"></i></div>
                    <div>
                        <div class="dash-card-number">{{ $stats['appeals_pending'] }}</div>
                        <p class="dash-card-label">Recursos em análise</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Próximos eventos --}}
        <h2 class="dash-section-title">Próximos eventos</h2>

        @if ($upcoming->isEmpty())
            <div class="card dash-list">
                <div class="dash-event">
                    <p class="dash-card-label mb-0">Nenhum evento futuro cadastrado.</p>
                </div>
            </div>
        @else
            <div class="card dash-list">
                @foreach ($upcoming as $event)
                    <div class="dash-event">
                        <div class="dash-event-icon">{!! $event['icon'] !!}</div>

                        <div class="dash-event-info">
                            <strong>{{ $event['label'] }}</strong>
                            <small>{{ $event['when'] }}</small>
                        </div>

                        <span class="dash-event-days {{ $event['tone'] }}">
                            {{ $event['countdown'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Gráfico: inscrições e vagas por curso --}}
        <h2 class="dash-section-title">Inscrições e vagas por curso</h2>
        <div class="card dash-list">
            <div class="dash-chart-box">
                @if (count($chart['labels']))
                    <div class="dash-chart" style="height: {{ max(220, count($chart['labels']) * 70) }}px">
                        <canvas id="courseChart"></canvas>
                    </div>
                @else
                    <p class="dash-card-label mb-0">Ainda não há cursos cadastrados neste processo.</p>
                @endif
            </div>
        </div>

        {{-- Gráfico: inscrições por dia --}}
        <h2 class="dash-section-title">Inscrições por dia</h2>
        <div class="card dash-list">
            <div class="dash-chart-box">
                @if (count($daily['labels']))
                    <p class="dash-card-label mb-3">
                        Total no período: <strong>{{ $daily['total'] }}</strong> inscrições
                    </p>
                    <div class="dash-chart" style="height: 260px">
                        <canvas id="dailyChart"></canvas>
                    </div>
                @else
                    <p class="dash-card-label mb-0">As inscrições ainda não começaram.</p>
                @endif
            </div>
        </div>

        {{-- Andamento do processo --}}
        <h2 class="dash-section-title">Andamento do processo</h2>
        <div class="card dash-list">
            <ol class="dash-timeline">
                @foreach ($timeline as $step)
                    <li class="dash-tl-item {{ $step['state'] }}">
                        <div class="dash-tl-dot">{!! $step['icon'] !!}</div>

                        <div class="dash-tl-info">
                            <strong>{{ $step['label'] }}</strong>
                            <small>{{ $step['when'] }}</small>
                        </div>

                        <span class="dash-tl-tag">{{ $step['tag'] }}</span>
                    </li>
                @endforeach
            </ol>
        </div>

        {{-- Ações rápidas --}}
        <h2 class="dash-section-title">Ações rápidas</h2>
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <a href="{{ route('admin.exam.show') }}" class="dash-action">
                    <i class="bi bi-geo-alt"></i>
                    <span>
                        Locais de prova
                        <small>{{ $process->isLocationReleased() ? 'Já divulgados' : 'Ainda não divulgados' }}</small>
                    </span>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="#" class="dash-action">
                    <i class="bi bi-list-ol"></i>
                    <span>
                        Resultado final
                        <small>{{ $process->isResultReleased() ? 'Já divulgado' : 'Ainda não divulgado' }}</small>
                    </span>
                </a>
            </div>

            <div class="col-12 col-md-4">
                <a href="{{ route('admin.reports.general') }}" class="dash-action">
                    <i class="bi bi-bar-chart-line"></i>
                    <span>
                        Relatórios
                        <small>Listas, resumos e PDF</small>
                    </span>
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
@endpush

@if (count($chart['labels']))
    @push('scripts')
        <script>
            const chartData = @json($chart);
            const css = getComputedStyle(document.documentElement);

            Chart.defaults.font.family = css.getPropertyValue('--font-body').trim();

            // Quebra nomes compridos em várias linhas
            const wrap = (text, max = 22) => {
                const lines = [];
                let line = '';
                text.split(' ').forEach(word => {
                    if (line && (line + ' ' + word).length > max) {
                        lines.push(line);
                        line = word;
                    } else {
                        line = line ? line + ' ' + word : word;
                    }
                });
                lines.push(line);
                return lines;
            };

            new Chart(document.getElementById('courseChart'), {
                type: 'bar',
                data: {
                    labels: chartData.labels.map(l => wrap(l)),
                    datasets: [{
                            label: 'Inscrições',
                            data: chartData.inscritos,
                            backgroundColor: css.getPropertyValue('--color-teal').trim(),
                            borderRadius: 6,
                        },
                        {
                            label: 'Vagas',
                            data: chartData.vagas,
                            backgroundColor: css.getPropertyValue('--color-navy').trim(),
                            borderRadius: 6,
                        },
                    ],
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        },
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                title: items => chartData.labels[items[0].dataIndex],
                                afterBody: items => {
                                    const i = items[0].dataIndex;
                                    const vagas = chartData.vagas[i];
                                    if (!vagas) return '';
                                    const ratio = (chartData.inscritos[i] / vagas).toFixed(1).replace('.', ',');
                                    return 'Concorrência: ' + ratio + ' candidato(s) por vaga';
                                },
                            },
                        },
                    },
                },
            });
        </script>
    @endpush
@endif

@if (count($daily['labels']))
    @push('scripts')
        <script>
            (() => {
                const daily = @json($daily);
                const css = getComputedStyle(document.documentElement);

                Chart.defaults.font.family = css.getPropertyValue('--font-body').trim();

                new Chart(document.getElementById('dailyChart'), {
                    type: 'bar',
                    data: {
                        labels: daily.labels,
                        datasets: [{
                            label: 'Inscrições',
                            data: daily.values,
                            backgroundColor: css.getPropertyValue('--color-teal').trim(),
                            borderRadius: 4,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    maxRotation: 0,
                                    autoSkip: true
                                }
                            },
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            },
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                    },
                });
            })();
        </script>
    @endpush
@endif
