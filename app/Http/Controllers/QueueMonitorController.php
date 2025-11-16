<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class QueueMonitorController extends Controller
{
    public function index()
    {
        $pending = DB::table('jobs')->count();
        $failed = DB::table('failed_jobs')->count();

        // Estimar processados
        $totalProcessed = DB::table('job_batches')->sum('total_jobs');
        $processed = max($totalProcessed - ($pending + $failed), 0);

        $totalJobs = $pending + $processed + $failed;
        $successRate = $totalJobs > 0 ? round(($processed / $totalJobs) * 100, 2) : 0;
        $failureRate = $totalJobs > 0 ? round(($failed / $totalJobs) * 100, 2) : 0;

        // Logs recentes de falha
        $failedJobs = DB::table('failed_jobs')
            ->orderByDesc('failed_at')
            ->limit(10)
            ->get(['id', 'connection', 'queue', 'failed_at', 'exception']);

        return view('system.private.queue-monitor', [
            'pending' => $pending,
            'processed' => $processed,
            'failed' => $failed,
            'successRate' => $successRate,
            'failureRate' => $failureRate,
            'failedJobs' => $failedJobs,
        ]);
    }

    public function retryFailed()
    {
        Artisan::call('queue:retry', ['id' => 'all']);
        return redirect()->back()->with('success', 'Todos os jobs com falha foram reenviados para a fila.');
    }

    public function flushFailed()
    {
        Artisan::call('queue:flush');
        return redirect()->back()->with('success', 'Jobs com falha limpos com sucesso.');
    }
}