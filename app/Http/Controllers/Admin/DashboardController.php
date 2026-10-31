<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appeal;
use App\Models\Lgbt;
use App\Models\Pne;
use App\Models\Process;
use App\Services\ChartService;
use Barryvdh\DomPDF\Facade\Pdf;

class DashboardController extends Controller
{
    public function index(ChartService $cs)
    {
        $process = Process::current();

        $stats = [
            // Para analisar
            'pne_pending'     => Pne::where('status', Appeal::STATUS_PENDING)->count(),
            'lgbt_pending'    => Lgbt::where('status', Appeal::STATUS_PENDING)->count(),
            'appeals_pending' => Appeal::pending()->count(),
        ];

        $upcoming = $this->upcomingEvents($process);
        $timeline = $this->timeline($process);
        $alerts   = $this->alerts($process);
        $chart    = $cs->courseChart($process);
        $daily    = $cs->dailyChart($process);

        return view('admin.dashboard.index', compact('stats', 'upcoming', 'timeline', 'alerts', 'chart', 'daily'));
    }

    /**
     * Os 3 próximos eventos do calendário (ou em andamento).
     */
    private function upcomingEvents(?Process $process)
    {
        if (!$process) {
            return collect();
        }

        $today = today();

        return collect($process->calendar())
            ->map(function ($item) use ($process, $today) {
                $isPeriod = $item['type'] === 'period';
                $start    = $isPeriod ? $item['start'] : $item['date'];
                $end      = $isPeriod ? $item['end'] : $item['date'];

                // Sem data, ou já passou: não mostra
                if (!$start || !$end || $end->lt($today)) {
                    return null;
                }

                $ongoing = $start->lte($today);
                $target  = $ongoing ? $end : $start;
                $days    = (int) $today->diffInDays($target);

                if ($ongoing) {
                    $countdown = $days === 0 ? 'termina hoje' : "termina em {$days} dia(s)";
                    $tone      = 'now';
                } else {
                    $countdown = match (true) {
                        $days === 0 => 'hoje',
                        $days === 1 => 'amanhã',
                        default     => "em {$days} dias",
                    };
                    $tone = $days <= 7 ? 'soon' : 'later';
                }

                return [
                    'label'     => $item['label'],
                    'icon'      => $item['icon'],
                    'when'      => $isPeriod
                        ? $process->formatPeriod($start, $end)
                        : $process->formatDate($start),
                    'countdown' => $countdown,
                    'tone'      => $tone,
                    'target'    => $target,
                ];
            })
            ->filter()
            ->sortBy(fn($event) => $event['target']->timestamp)
            ->take(3)
            ->values();
    }

    /**
     * Todas as etapas do processo, com o estado de cada uma.
     */
    private function timeline(?Process $process)
    {
        if (!$process) {
            return collect();
        }

        $today = today();

        return collect($process->calendar())->map(function ($item) use ($process, $today) {
            $isPeriod = $item['type'] === 'period';
            $start    = $isPeriod ? $item['start'] : $item['date'];
            $end      = $isPeriod ? $item['end'] : $item['date'];

            if (!$start || !$end) {
                $state = 'empty';
                $when  = 'Data não definida';
            } else {
                $state = match (true) {
                    $end->lt($today)    => 'done',
                    $start->lte($today) => 'now',
                    default             => 'next',
                };

                $when = $isPeriod
                    ? $process->formatPeriod($start, $end)
                    : $process->formatDate($start);
            }

            return [
                'label' => $item['label'],
                'icon'  => $item['icon'],
                'when'  => $when,
                'state' => $state,
                'tag'   => match ($state) {
                    'done'  => 'Concluído',
                    'now'   => 'Em andamento',
                    'next'  => 'Aguardando',
                    default => 'Sem data',
                },
            ];
        });
    }

    /**
     * Avisos de prazos que chegaram e ainda não foram cumpridos.
     */
    private function alerts(?Process $process): array
    {
        if (!$process) {
            return [];
        }

        $today  = today();
        $alerts = [];

        // Locais de prova
        if (
            $process->location_publish
            && $today->gte($process->location_publish)
            && !$process->isLocationReleased()
        ) {
            $alerts[] = $today->isSameDay($process->location_publish)
                ? 'Hoje é o dia de divulgar os locais de prova.'
                : 'A data de divulgação dos locais de prova (' . $process->formatDate($process->location_publish) . ') já passou e eles ainda não foram divulgados.';
        }

        // Resultado final
        if (
            $process->result_publish
            && $today->gte($process->result_publish)
            && !$process->isResultReleased()
        ) {
            $alerts[] = $today->isSameDay($process->result_publish)
                ? 'Hoje é o dia de divulgar o resultado final.'
                : 'A data de divulgação do resultado (' . $process->formatDate($process->result_publish) . ') já passou e ele ainda não foi divulgado.';
        }

        return $alerts;
    }

    /**
     * Relatório em PDF: inscrições por dia.
     */
    public function dailyPdf()
    {
        $process = Process::current();
        abort_if(!$process, 404);

        $rows = $this->dailyRows($process);

        $pdf = Pdf::loadView('admin.reports.daily', [
            'process'     => $process,
            'rows'        => $rows,
            'total'       => $rows->sum('total'),
            'generatedAt' => now(),
        ]);

        return $pdf->download('inscricoes-por-dia-' . $process->year . '.pdf');
    }

    /**
     * Relatório em CSV: inscrições por dia.
     */
    public function dailyCsv()
    {
        $process = Process::current();
        abort_if(!$process, 404);

        $rows = $this->dailyRows($process);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            fwrite($out, "\xEF\xBB\xBF"); // faz o Excel reconhecer os acentos
            fputcsv($out, ['Data', 'Dia da semana', 'Inscrições', 'Acumulado'], ';', '"', '');

            foreach ($rows as $row) {
                fputcsv($out, [$row['date'], $row['weekday'], $row['total'], $row['accumulated']], ';', '"', '');
            }

            fclose($out);
        }, 'inscricoes-por-dia-' . $process->year . '.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Linhas do relatório: data, dia da semana, total do dia e acumulado.
     */
    private function dailyRows(Process $process)
    {
        $accumulated = 0;

        return collect($process->dailyInscriptions())->map(function ($day) use (&$accumulated) {
            $accumulated += $day['total'];

            return [
                'date'        => $day['date']->format('d/m/Y'),
                'weekday'     => ucfirst($day['date']->copy()->locale('pt_BR')->isoFormat('dddd')),
                'total'       => $day['total'],
                'accumulated' => $accumulated,
            ];
        });
    }
}
