<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicRequest;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\CertificateRequest;
use App\Http\Requests\FamilyRequest;
use App\Http\Requests\OtherRequest;
use App\Http\Requests\PersonalRequest;
use App\Models\Course;
use App\Models\Degree;
use App\Models\Disability;
use App\Models\Document;
use App\Models\Gender;
use App\Models\HealthIssue;
use App\Models\Nationality;
use App\Models\Resource;
use App\Models\School;
use App\Models\SelectionProcess;
use App\Services\InscriptionService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    public function start(): View
    {
        $user = Auth::user();

        $selection_process = SelectionProcess::current();

        if (!$selection_process || !$selection_process?->isInscriptionOpen()) {
            abort(404);
        }

        return view('inscription.start', compact('user'));
    }
    
    public function show(): View | RedirectResponse
    {
        $user = Auth::user();

        // Segurança extra caso acessem direto sem ter inscrição
        if (!$user->inscription()->exists()) {
            return redirect()
                ->route('inscription.step.start')
                ->with('warning', 'Você ainda não possui inscrição ativa.');
        }

        // Carrega tudo que o painel precisa
        $user->load([
            'inscription.exam_result.examLocation',
            'inscription.exam_result.completedCall',
        ]);

        $inscription  = $user->inscription;
        $examResult   = $inscription->exam_result;
        $examLocation = $examResult?->examLocation;

        $exam = $examResult; // O EXAM REAL — o model completo

        $call = $examResult?->completedCall;

        return view('inscription.show', compact('user', 'exam', 'examResult', 'call'));
    }

    // Passo 1: Dados pessoais
    public function personal(): View|RedirectResponse
    {
        $nationalities = Nationality::all();
        $documents = Document::all();
        $genders = Gender::all();

        return view('inscription.step.personal', [
            'nationalities' => $nationalities,
            'documents' => $documents,
            'genders' => $genders
        ]);
    }

    // Gravar Dados de Passo 1
    public function personalStore(PersonalRequest $request): RedirectResponse
    {
        $data = $request->except(['_token', 'authorization']);

        if (($data['social_name_option'] ?? null) == "2") {
            $data['social_name'] = null;
        }

        if ($request->hasFile('authorization')) {
            $path = $request->file('authorization')->store('authorizations', 'public');
            $data['authorization'] = $path; // salva só o caminho
        }

        session()->put('step1', $data);
        session()->put('step1_done', true);

        return redirect()->route('inscription.step.certificate');
    }

    // Passo 2: Certidão de Nascimento
    public function certificate(): View|RedirectResponse
    {
        if (!session('step1_done')) { // Verifica se o passo 1 foi concluído
            return redirect()->route('inscription.step.personal');
        }

        return view('inscription.step.certificate');
    }
    // Gravar Dados de Passo 2
    public function certificateStore(CertificateRequest $request): Response|RedirectResponse
    {
        session()->put('step2', $request->except('_token'));
        session()->put('step2_done', true);

        return redirect()->route('inscription.step.address');
    }

    // Passo 3: Endereço
    public function address(): View|RedirectResponse
    {
        if (!session('step2_done')) { // Verifica se o passo 2 foi concluído
            return redirect()->route('inscription.step.certificate');
        }

        return view('inscription.step.address');
    }
    // Gravar Dados de Passo 3
    public function addressStore(AddressRequest $request): RedirectResponse
    {
        session()->put('step3', $request->except('_token'));
        session()->put('step3_done', true);

        return redirect()->route('inscription.step.academic');
    }

    // Passo 4: Dados Acadêmicos
    public function academic(): View|RedirectResponse
    {
        if (!session('step3_done')) { // Verifica se o passo 3 foi concluído
            return redirect()->route('inscription.step.address');
        }

        $schools = School::all();

        return view('inscription.step.academic', [
            'schools' => $schools
        ]);
    }
    // Gravar Dados de Passo 4
    public function academicStore(AcademicRequest $request): Response|RedirectResponse
    {
        session()->put('step4', $request->except(['_token']));
        session()->put('step4_done', true);

        return redirect()->route('inscription.step.family');
    }

    // Passo 5: Dados Familiares
    public function family(): View|RedirectResponse
    {
        if (!session('step4_done')) { // Verifica se o passo 4 foi concluído
            return redirect()->route('inscription.step.academic');
        }

        $degrees = Degree::all();

        return view('inscription.step.family', [
            'degrees' => $degrees
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

        return redirect()->route('inscription.step.other');
    }

    // Passo 6: Outras Informações
    public function other(): View|RedirectResponse
    {
        if (!session('step5_done')) { // Verifica se o passo 3 foi concluído
            return redirect()->route('inscription.step.family');
        }

        // Array de acessibilidade
        $options = [
            '1' => 'Sim',
            '2' => 'Não',
        ];

        $disabilities = Disability::all();
        
        $accessibilityResources = Resource::all();

        $healthIssues = HealthIssue::all();

        return view('inscription.step.others', compact('options', 'disabilities', 'accessibilityResources', 'healthIssues'));
    }

    // Gravar Dados de Passo 6
    public function otherStore(OtherRequest $request): Response|RedirectResponse
    {
        $data = $request->except(['_token', 'pne_report']);

        if ($data['health'] != 1) {
            $data['health_description'] = null;
        }

        if ($request->hasFile('pne_report')) {
            $path = $request->file('pne_report')->store('reports', 'public');
            $data['pne_report'] = $path; // salva só o caminho
        }

        if ($data['pne'] != 1) {
            $data['accessibility_description'] = null;
            $data['pne_description'] = null;
        }

        if ($data['social_program'] != 1) {
            $data['nis'] = null;
        }

        session()->put('step6', $data);
        session()->put('step6_done', true); // Marca como concluído

        return redirect()->route('inscription.step.course');
    }

    // Passo 7: Curso Pretendido
    public function course(): View|RedirectResponse
    {
        if (!session('step1_done') && !session('step2_done') && !session('step3_done') && !session('step4_done') && !session('step5_done') && !session('step6_done')) { // Verifica se o passo 6 foi concluído
            return redirect()->route('inscription.step.other');
        }

        return view('inscription.step.course', [
            'courses' => Course::all()
        ]);
    }

    // Gravar Dados de Passo 7
    public function courseStore(Request $request)
    {
        session()->put('step7', $request->except(['_token']));
        session()->put('step7_done', true);

        return redirect()->route('inscription.step.confirm');
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
            return redirect()->route('inscription.step.start'); // <= CORRIGIR ROTA
        }

        // Retorna a view com os dados necessários
        return view('inscription.step.confirm', array_merge(
            $steps->all()
        ));
    }

    // Gravar Dados de Passo 8
    public function inscriptionStore(Request $request, InscriptionService $inscriptionService)
    {
        try {

            $request->validate([
                'agree_terms' => 'accepted'
            ]);

            $inscriptionService->store(); // Grava os dados através do service

            return redirect()->route('inscription.user.show')->with('success', 'Inscrição efetuada com sucesso!');
        } catch (QueryException $e) {

            if (str_contains($e->getMessage(), 'SQLSTATE[22001]')) {
                return redirect()->route('failed')
                    ->with(
                        'error',
                        'Um ou mais campos excedem o tamanho permitido. Corrija os dados e tente novamente.'
                    );

                return redirect()->route('failed')->with(
                    'error',
                    'Um ou mais campos excedem o tamanho permitido. Corrija os dados e tente novamente.',
                );
            }

            if (app()->environment('local')) {
                throw $e;
            }

            return redirect()->route('failed')->with([
                'status' => [
                    'alert-type' => 'danger',
                    'message' => 'Erro ao salvar os dados. Verifique se todos os campos estão corretos.',
                ]
            ]);
        } catch (\Exception $e) {

            return redirect()->route('failed')->with(
                'danger',
                $e->getMessage() === 'Inscrição já realizada.'
                    ? 'Você já se inscreveu.'
                    : 'Erro inesperado. Por favor, tente novamente.' . $e->getMessage(),
            );
        }
    }
}
