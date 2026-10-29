<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\SimpleImport;
use App\Models\Exam;
use App\Models\Lgbt;
use App\Models\Process;
use App\Models\Result;
use App\Services\ExamRankingService;
use DomainException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    protected $rankingService;

    public function __construct(ExamRankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    public const LIST_MULTIPLIER = 3;

    /**
     * Lista de classificação do processo atual no painel administrativo.
     */
    public function index(Request $request): View
    {
        $process = Process::current() ?? abort(404);

        $situation = in_array($request->query('situation'), ['classified', 'eliminated'], true)
            ? $request->query('situation')
            : 'all';

        // Busca por nome (civil ou social) ou número da inscrição
        $search = trim((string) $request->query('q', ''));

        // Resultados com nota deste processo, junto dos dados do candidato
        $base = Result::query()
            ->join('inscriptions', 'results.inscription_id', '=', 'inscriptions.id')
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->leftJoin('lgbts', function ($join) {
                $join->on('lgbts.user_id', '=', 'users.id')
                    ->where('lgbts.status', Lgbt::STATUS_ACCEPTED);
            })
            ->where('inscriptions.process_id', $process->id)
            ->whereNotNull('results.score');

        // Resumo do topo
        $stats = (clone $base)
            ->selectRaw('COUNT(*) as scored')
            // Alias diferente de "classified": o model Result converte essa coluna para boolean
            ->selectRaw('COALESCE(SUM(results.classified), 0) as classified_count')
            ->selectRaw('MIN(CASE WHEN results.classified = 1 THEN results.score END) as cutoff')
            ->first();

        // Empates em nota e idade: o edital não define o desempate seguinte
        $tied = (clone $base)
            ->selectRaw('results.score, users.birth, COUNT(*) as total')
            ->groupBy('results.score', 'users.birth')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        $tiedKeys = $tied->mapWithKeys(fn($t) => [$t->score . '|' . $t->birth => true]);

        $vacancies = (int) $process->courses()->sum('course_process.vacancies');
        $limit = $vacancies * ExamRankingService::LIST_MULTIPLIER;

        // Lista paginada
        $results = (clone $base)
            ->when($situation === 'classified', fn($q) => $q->where('results.classified', true))
            ->when($situation === 'eliminated', fn($q) => $q->where('results.classified', false))
            ->when($search !== '', function ($q) use ($search) {
                $like = '%' . addcslashes($search, '%_\\') . '%';

                $q->where(function ($q) use ($like, $search) {
                    $q->where('users.name', 'like', $like)
                        ->orWhere('lgbts.name', 'like', $like);

                    if (ctype_digit($search)) {
                        $q->orWhere('results.inscription_id', (int) $search);
                    }
                });
            })
            ->orderBy('results.ranking')
            ->orderBy('results.inscription_id')
            ->select([
                'results.inscription_id',
                'results.ranking',
                'results.score',
                'results.classified',
                'users.name',
                'users.birth',
                DB::raw('lgbts.name as social_name'),
            ])
            ->paginate(50)
            ->withQueryString();

        return view('admin.import.index', compact(
            'process',
            'results',
            'stats',
            'tied',
            'tiedKeys',
            'situation',
            'search',
            'vacancies',
            'limit'
        ));
    }

    // Exibe o formulário de importação
    public function create(): View
    {
        return view('admin.import.create');
    }

    /**
     * Importa as notas de um arquivo .xlsx e calcula a classificação.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx|max:10240',
        ], [
            'file.required' => 'Carregue um arquivo.',
            'file.file'     => 'O arquivo está corrompido.',
            'file.mimes'    => 'Envie um arquivo no formato .xlsx.',
            'file.max'      => 'O arquivo não pode ultrapassar 10 MB.',
        ]);

        try {
            $process = Process::current();

            if (! $process) {
                return $this->invalidFileResponse('Nenhum processo seletivo cadastrado.');
            }

            // Reimportar mudaria notas e classificação já visíveis aos candidatos
            if ($process->isResultReleased()) {
                return $this->invalidFileResponse(
                    'O resultado já foi liberado aos candidatos. Retire-o do ar antes de importar novas notas.'
                );
            }

            // Candidatos alocados (com prova agendada) neste processo
            $allocated = Exam::whereHas('inscription', fn($q) => $q->forProcess($process->id));

            if (! (clone $allocated)->exists()) {
                return $this->invalidFileResponse('Nenhuma prova foi agendada.');
            }

            $import = new SimpleImport();
            Excel::import($import, $request->file('file'));

            $rows = $import->rows->toArray();

            if (count($rows) < 2) {
                return $this->invalidFileResponse('Arquivo vazio ou inválido.');
            }

            $expectedHeaders = [
                'inscription_id',
                'user_id',
                'user_cpf',
                'user_name',
                'user_birth',
                'points',
            ];
            $headers = array_map(
                fn($header) => strtolower(trim((string) $header)),
                $rows[0] ?? []
            );

            if ($headers !== $expectedHeaders) {
                return $this->invalidFileResponse(
                    'Cabeçalhos inválidos. Use: ' . implode(', ', $expectedHeaders) . '.'
                );
            }

            $updates = [];
            $errors = [];

            foreach (array_slice($rows, 1) as $index => $row) {
                $line = $index + 2;

                if (count($row) < count($expectedHeaders)) {
                    $errors[] = "Linha {$line}: todas as seis colunas são obrigatórias.";
                    continue;
                }

                $inscriptionId = $row[0];
                $points = $row[5];

                if (!is_numeric($inscriptionId) || (float) $inscriptionId < 1 || floor((float) $inscriptionId) !== (float) $inscriptionId) {
                    $errors[] = "Linha {$line}: inscription_id inválido.";
                    continue;
                }

                if (!is_numeric($points) || (float) $points < 0 || floor((float) $points) !== (float) $points) {
                    $errors[] = "Linha {$line}: points deve ser um número inteiro igual ou maior que zero.";
                    continue;
                }

                $normalizedInscriptionId = (int) $inscriptionId;

                if (isset($updates[$normalizedInscriptionId])) {
                    $errors[] = "Linha {$line}: inscription_id duplicado no arquivo.";
                    continue;
                }

                $updates[$normalizedInscriptionId] = (int) $points;
            }

            if ($errors) {
                return $this->invalidFileResponse(implode(' ', $errors));
            }

            if (!$updates) {
                return $this->invalidFileResponse('O arquivo não possui notas válidas para importar.');
            }

            // Toda inscrição da planilha precisa ter prova agendada neste processo
            $inscriptionIds = array_keys($updates);
            $foundInscriptionIds = (clone $allocated)
                ->whereIn('inscription_id', $inscriptionIds)
                ->pluck('inscription_id')
                ->map(fn($id) => (int) $id)
                ->all();
            $missingInscriptionIds = array_values(array_diff($inscriptionIds, $foundInscriptionIds));

            if ($missingInscriptionIds) {
                return $this->invalidFileResponse(
                    'As inscrições a seguir não têm prova agendada neste processo: '
                        . implode(', ', $missingInscriptionIds) . '.'
                );
            }

            $withoutScore = (clone $allocated)->count() - count($updates);
            $now = now();
            $summary = [];

            DB::transaction(function () use ($updates, $process, $now, &$summary) {
                // Uma importação substitui integralmente as notas e classificações DESTE processo.
                Result::whereHas('inscription', fn($q) => $q->forProcess($process->id))
                    ->update(['score' => null, 'ranking' => null, 'classified' => false]);

                $rows = [];

                foreach ($updates as $inscriptionId => $points) {
                    $rows[] = [
                        'inscription_id' => $inscriptionId,
                        'score'          => $points,
                        'created_at'     => $now,
                        'updated_at'     => $now,
                    ];
                }

                // exige índice unique em results.inscription_id
                foreach (array_chunk($rows, 1000) as $batch) {
                    Result::upsert($batch, ['inscription_id'], ['score', 'updated_at']);
                }

                $summary = $this->rankingService->calculate($process);
            });

            $message = count($updates) . ' notas importadas. '
                . "{$summary['classified']} candidatos classificados (limite de {$summary['limit']} pelo edital, "
                . 'mais os empatados com o último).';

            if ($withoutScore > 0) {
                $message .= " {$withoutScore} candidato(s) com prova agendada não constam na planilha e ficaram sem nota.";
            }

            if ($summary['tied'] > 0) {
                $message .= " Atenção: {$summary['tied']} candidato(s) empatam em nota e idade com outro e dividem a mesma posição.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (DomainException $e) {
            return $this->invalidFileResponse($e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao importar: ' . $e->getMessage()
            ], 500);
        }
    }

    private function invalidFileResponse(string $message)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 422);
    }
}
