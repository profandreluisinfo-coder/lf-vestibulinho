<?php

namespace App\Http\Controllers\App;

use App\Models\User;
use App\Models\Course;
use App\Models\Notice;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use App\Http\Requests\OtherRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\CourseRequest;
use App\Http\Requests\FamilyRequest;
use App\Services\InscriptionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use App\Http\Requests\AcademicRequest;
use Illuminate\Database\QueryException;
use App\Http\Requests\CertificateRequest;
use App\Http\Controllers\Controller;

class InscriptionController extends Controller
{
    /**
     * Exibe a lista de candidatos com inscrição ativa.
     *
     * @return View
     */
    public function index(): View
    {
        // Agora apenas retorna a view, os dados virão via AJAX
        return view('inscriptions.admin.index');
    }

    /**
     * Retorna os dados paginados para o DataTables via AJAX
     *
     * Os dados serão exibidos na tabela de inscrições ativas no dashboard, na view "inscriptions.admin.index".
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        // Captura os parâmetros do DataTables
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');

        // Mapeamento de colunas para ordenação
        $columns = ['inscriptions.id', 'users.name', 'users.cpf'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'inscriptions.id';

        // Query base
        $query = User::whereHas('inscription')
            ->with('inscription')
            ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
            ->select('users.*', 'inscriptions.id as inscription_id');

        // Aplicar busca se houver
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', "%{$searchValue}%")
                    ->orWhere('users.cpf', 'like', "%{$searchValue}%")
                    ->orWhere('inscriptions.id', 'like', "%{$searchValue}%");
            });
        }

        // Total de registros (sem filtro)
        $totalRecords = User::whereHas('inscription')->count();

        // Total de registros filtrados
        $totalFiltered = $query->count();

        // Aplicar ordenação e paginação
        $users = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Formatar dados para o DataTables
        $data = $users->map(function ($user) {
            return [
                'inscription_id' => $user->inscription?->id ?? $user->inscription_id,
                'name' => $user->name,
                'cpf' => $user->cpf,
                'user_id' => $user->id,
                'actions' => view('inscriptions.admin.inscription-actions', compact('user'))->render()
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    /**
     * Exibe a lista de candidatos com deficiência.
     *
     * @return View
     */
    public function pcd(): View
    {
        // Não carrega mais os dados aqui, apenas retorna a view vazia
        return view('inscriptions.admin.pcd');
    }

    /**
     * Retorna os dados paginados para o DataTables via AJAX
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPcd(Request $request): JsonResponse
    {
        // Captura os parâmetros do DataTables
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir', 'asc');

        // Mapeamento de colunas para ordenação
        $columns = ['id', 'name', 'accessibility'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        // Query base
        $query = User::where('pne', true)
            ->whereHas('inscription')
            ->with(['inscription', 'user_detail']);

        // Aplicar busca se houver
        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('cpf', 'like', "%{$searchValue}%")
                    ->orWhereHas('inscription', function ($subQ) use ($searchValue) {
                        $subQ->where('id', 'like', "%{$searchValue}%");
                    });
            });
        }

        // Total de registros (sem filtro)
        $totalRecords = User::where('pne', true)->whereHas('inscription')->count();

        // Total de registros filtrados
        $totalFiltered = $query->count();

        // Aplicar ordenação e paginação
        $users = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Formatar dados para o DataTables
        $data = $users->map(function ($user) {
            return [
                'inscription_id' => $user->inscription?->id,
                'name' => $user->name,
                'accessibility' => $user->user_detail?->accessibility ?? '-',
                'user_id' => $user->id,
                'actions' => view('inscriptions.admin.pcd-actions', compact('user'))->render()
            ];
        });

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    /**
     * Exibe uma lista de candidatos que utilizaram nome social ('social_name').
     *
     * @return View
     */
    public function socialName(): View
    {
        $users = User::whereNotNull('social_name')
            ->whereHas('inscription') // ou 'inscriptions', dependendo da relação
            ->with('inscription')     // dados da inscrição na view
            ->get();

        view()->share('users', $users);

        return view('inscriptions.admin.social-name');
    }

    /**
     * Exibe a ficha de inscrição de um candidato especificado.
     *
     * @param string $id O ID do candidato, criptografado.
     *
     * @return View A view com a ficha de inscri o do candidato.
     */
    public function show($id): View
    {
        $id = Crypt::decrypt($id);

        $user = User::find($id);

        return view('inscriptions.admin.show')->with('user', $user);
    }

    // Passo 1: Dados pessoais
    public function personal(): View|RedirectResponse
    {
        return view('forms.personal', [
            'bg' =>  session()->has('step1_done') ? 'bg-success' : 'bg-secondary',
            'step' => 1,
            'title' => "Dados Pessoais",
            'nationalities' => [
                '1' => 'Brasileira',
                '2' => 'Estrangeira'
            ],
            'documents' => [
                '1' => 'RG - REGISTRO GERAL',
                '2' => 'CIN - CARTEIRA DE IDENTIDADE NACIONAL',
                '3' => 'RNE - REGISTRO NACIONAL DE ESTRANGEIRO'
            ],
            'genders' => [
                '1' => 'Masculino',
                '2' => 'Feminino',
                '3' => 'Outro',
                '4' => 'Prefiro não informar'
            ],
            'options' => [
                '1' => 'Sim',
                '2' => 'Não',
            ],
            'notice' => Notice::first()
        ]);
    }
    // Gravar Dados de Passo 1
    public function personalStore(UserRequest $request): RedirectResponse
    {
        session()->put('step1', $request->except(['_token']));
        session()->put('step1_done', true);

        return redirect()->route('step.certificate');
    }

    // Passo 2: Certidão de Nascimento
    public function certificate(): View|RedirectResponse
    {
        return view('forms.certificate', [
            'bg' =>  session()->has('step2_done') ? 'bg-success' : 'bg-secondary',
            'step' => 2,
            'title' => 'Certidão de Nascimento'
        ]);
    }
    // Gravar Dados de Passo 2
    public function certificateStore(CertificateRequest $request): Response|RedirectResponse
    {
        session()->put('step2', $request->except('_token'));
        session()->put('step2_done', true);

        return redirect()->route('step.address');
    }

    // Passo 3: Endereço
    public function address(): View|RedirectResponse
    {
        return view('forms.address', [
            'bg' =>  session()->has('step3_done') ? 'bg-success' : 'bg-secondary',
            'step' => 3,
            'title' => 'Endereço'
        ]);
    }
    // Gravar Dados de Passo 3
    public function addressStore(AddressRequest $request): RedirectResponse
    {
        session()->put('step3', $request->except('_token'));
        session()->put('step3_done', true);

        return redirect()->route('step.academic');
    }

    // Passo 4: Dados Acadêmicos
    public function academic(): View|RedirectResponse
    {
        $schools = [
            "EM ALCIONE AP FERNANDES PEREIRA",
            "EM ALFREDO DONAIRE",
            "EM ANDRÉ DE NADAI",
            "EM ARCO ÍRIS",
            "EM BORBOLETINHA AZUL",
            "EM DO CAIC ANDRÉ DE NADAI",
            "EM JARDIM BOM RETIRO",
            "EM JARDIM DENADAI",
            "EM JARDIM LÚCIA",
            "EM JARDIM MARIA ANTONIA",
            "EM JARDIM SÃO JUDAS TADEU",
            "EM JOSÉ DE ANCHIETA",
            "EM LASQUINHA DE GENTE",
            "EM MAGDALENA MARIA VEDOVATTO CALLEGARI",
            "EM MUNDO ALEGRE DA CRIANÇA",
            "EM OSWALDO RONCOLATTO",
            "EM PALHACINHO DENGOSO",
            "EM PARQUE BANDEIRANTES II",
            "EM PARQUE DAS NAÇÕES",
            "EM PARQUE RESIDENCIAL REGINA",
            "EM PROFª MARTHA SMOLII DOMINGUES",
            "EM RAMONA CANHETE PINTO",
            "EM REINO DA GAROTADA",
            "EM SABIDINHO",
            "EM SANTO TOMAZIN",
            "EM VISCONDE DE SABUGOSA",
            "EM XODÓ DA TITIA",
            "EMEF ANTONIETTA CIA VIEL",
            "EMEF ANTONIO PALIOTO",
            "EMEF PROF ANÁLIA DE O. NASCIMENTO",
            "EMEF PROF ELIANA MINCHIN VAUGHAN",
            "EMEF PROF FLORA FERREIRA GOMES",
            "EMEF PROF NEUSA DE SOUZA CAMPOS",
            "EMEF PROF NILZA THOMAZINI",
            "EMEFr D AUGUSTA RAVAGNANI BASSO",
            "EMEFr MARIA APARECIDA DE JESUS SEGURA",
            "EM MARIA LUIZA CIA MEDEIROS",
            "EM JEANY LEMOS GONÇALVES RODRIGUES (RES. SANTA JOANA)",
            "EM DIRCE APARECIDA MENUZZO RICARDO (JARDIM DAS ESTÂNCIAS)"
        ];

        return view('forms.academic', [
            'bg' =>  session()->has('step4_done') ? 'bg-success' : 'bg-secondary',
            'step' => 4,
            'title' => "Dados Acadêmicos",
            'schools' => $schools
        ]);
    }
    // Gravar Dados de Passo 4
    public function academicStore(AcademicRequest $request): Response|RedirectResponse
    {
        session()->put('step4', $request->except(['_token']));
        session()->put('step4_done', true); // Marca como concluído

        return redirect()->route('step.family');
    }

    // Passo 5: Dados Familiares
    public function family(): View|RedirectResponse
    {
        return view('forms.family', [
            'bg' =>  session()->has('step5_done') ? 'bg-success' : 'bg-secondary',
            'step' => 5,
            'title' => "Filiação / Responsável Legal",
            'degrees' => [
                '1' => 'Padrasto',
                '2' => 'Madrasta',
                '3' => 'Avô(ó)',
                '4' => 'Tio(a)',
                '5' => 'Irmão(ã)',
                '6' => 'Primo(a)',
                '7' => 'Tio(a)',
                '8' => 'Outro',
            ],
            'options' => [
                '1' => 'Sim',
                '2' => 'Não',
            ],
        ]);
    }
    // Gravar Dados de Passo 5
    public function familyStore(FamilyRequest $request): Response|RedirectResponse
    {
        $data = $request->except(['_token']);

        if ($data['degree'] < 8) {
            $data['kinship'] = null;
        }

        if ($data['respLegalOption'] == "2") {
            $data['responsible'] = null;
            $data['degree'] = null;
            $data['kinship'] = null;
            $data['responsible_phone'] = null;
        }

        session()->put('step5', $data);
        session()->put('step5_done', true); // Marca como concluído

        return redirect()->route('step.other');
    }

    // Passo 6: Outras Informações
    public function other(): View|RedirectResponse
    {
        // Título do card-header
        $title = "Informações Complementares";

        // Array de acessibilidade
        $options = [
            '1' => 'Sim',
            '2' => 'Não',
        ];

        $disabilities = [
            1  => 'Auditiva - Leve',
            2  => 'Visual - Baixa Visão',
            3  => 'Intelectual - Leve',
            4  => 'Física - Amputado',
            6  => 'Auditiva - Moderada',
            7  => 'Auditiva - Severa',
            8  => 'Auditiva - Profunda',
            9  => 'Intelectual - Moderada',
            10 => 'Intelectual - Severo',
            11 => 'Transtorno Específico da Aprendizagem',
            12 => 'TDAH - Leve',
            13 => 'TDAH - Moderado',
            14 => 'TDAH - Severo',
            15 => 'Transtornos do Espectro Autista - Nível 1',
            16 => 'Transtornos do Espectro Autista - Nível 2',
            17 => 'Transtornos do Espectro Autista - Nível 3',
            18 => 'Síndrome de Down',
            19 => 'Múltiplas',
            20 => 'Física - Paralisia Cerebral',
            22 => 'Física - Hemiplegia',
            23 => 'Física - Hemiparesia',
            24 => 'Física - Monoplegia',
            25 => 'Física - Monoparesia',
            26 => 'Física - Paraplegia',
            27 => 'Física - Paraparesia',
            28 => 'Física - Tetraplegia',
            29 => 'Visual - Monocular',
            30 => 'Visual - Cego',
            31 => 'Visual /Cego - Surdocegueira'
        ];

        $healthIssues = [
            1  => 'Hipertensão Arterial',
            2  => 'Diabetes Mellitus',
            3  => 'Asma',
            4  => 'Doença Pulmonar Obstrutiva Crônica (DPOC)',
            5  => 'Depressão',
            6  => 'Ansiedade',
            7  => 'Obesidade',
            8  => 'Colesterol Alto (Dislipidemia)',
            9  => 'Doença Cardíaca (Cardiopatia)',
            10 => 'Osteoporose',
            11 => 'Artrite Reumatoide',
            12 => 'Alergias',
            13 => 'Enxaqueca Crônica',
            14 => 'Câncer',
            15 => 'Insuficiência Renal Crônica'
        ];

        return view('forms.others', compact('options', 'disabilities', 'healthIssues'))->with(
            [
                'bg' =>  session()->has('step6_done') ? 'bg-success' : 'bg-secondary',  // Marca como concluído
                'step' => 6,
                'title' => $title
            ]
        );
    }

    // Gravar Dados de Passo 6
    public function otherStore(OtherRequest $request): Response|RedirectResponse
    {
        $data = $request->except(['_token']);

        if ($data['health'] != 1) {
            $data['health_description'] = null;
        }
        if ($data['accessibility'] != 1) {
            $data['accessibility_description'] = null;
        }
        if ($data['social_program'] != 1) {
            $data['nis'] = null;
        }

        session()->put('step6', $data);
        session()->put('step6_done', true); // Marca como concluído

        return redirect()->route('step.course');
    }

    // Passo 7: Curso Pretendido
    public function course(): View|RedirectResponse
    {
        return view('forms.course', [
            'bg' =>  session()->has('step7_done') ? 'bg-success' : 'bg-secondary',
            'step' => 7,
            'title' => "Curso",
            'courses' => Course::all()
        ]);
    }

    // Gravar Dados de Passo 7
    public function courseStore(CourseRequest $request)
    {
        session()->put('step7', $request->except(['_token']));
        session()->put('step7_done', true);

        return redirect()->route('step.confirm');
    }

    // Passo 8: Confirmar Dados
    public function confirm(): View|RedirectResponse
    {
        // Coleta e une todos os dados das etapas
        $steps = collect(range(1, 7))->mapWithKeys(function ($step) {
            return ["step{$step}" => session()->get("step{$step}", [])];
        });

        $data = array_merge(...$steps->values()->toArray());

        // Se não houver dados, redireciona para o dashboard
        if (empty($data)) {
            return redirect()->route('dashboard.index');
        }

        // Define o título da etapa
        $title = "Confirmar Dados";

        // Retorna a view com os dados necessários
        return view('forms.confirm', array_merge(
            [
                'bg' =>  session()->has('step8_done') ? 'bg-success' : 'bg-secondary',
                'step' => 8,
                'title' => $title,
            ],
            $steps->all()
        ));
    }

    // Gravar Dados de Passo 8
    public function inscriptionStore(InscriptionService $inscriptionService)
    {
        try {
            $inscriptionService->store();

            return redirect()->route('candidate.profile')->with('success', 'Inscrição efetuada com sucesso!');
        } catch (QueryException $e) {
            Log::error('Erro no banco: ' . $e->getMessage());

            if (str_contains($e->getMessage(), 'SQLSTATE[22001]')) {
                return redirect()->route('step.failed')
                    ->with(
                        'error',
                        'Um ou mais campos excedem o tamanho permitido. Corrija os dados e tente novamente.'
                    );

                return redirect()->route('step.failed')->with(
                    'error',
                    'Um ou mais campos excedem o tamanho permitido. Corrija os dados e tente novamente.',
                );
            }

            if (app()->environment('local')) {
                throw $e;
            }

            return redirect()->route('step.failed')->with([
                'status' => [
                    'alert-type' => 'danger',
                    'message' => 'Erro ao salvar os dados. Verifique se todos os campos estão corretos.',
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Erro geral: ' . $e->getMessage());

            return redirect()->route('step.failed')->with(
                'danger',
                $e->getMessage() === 'Inscrição já realizada.'
                    ? 'Você já se inscreveu.'
                    : 'Erro inesperado. Por favor, tente novamente.' . $e->getMessage(),
            );
        }
    }

    /**
     * Renderiza a view para o formulário de inscrição.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   // resources\views\inscriptions\create.blade.php
        return view('inscriptions.create');
    }
}
