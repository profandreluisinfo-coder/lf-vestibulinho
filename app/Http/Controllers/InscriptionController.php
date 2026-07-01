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
use App\Models\HealthIssue;
use App\Models\Process;
use App\Models\Resource;
use App\Models\School;
use App\Services\InscriptionService;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class InscriptionController extends Controller
{
    public function start(): View
    {
        $user = Auth::user();
        $process = Process::current();

        if (! $process || ! $process?->isInscriptionOpen()) {
            abort(404);
        }

        $displayName = $user?->name && $user?->lgbt?->status === 'accepted' 
                    ? $user?->lgbt?->name 
                    : $user?->name;
        $name = $user?->name;
        $initials = $this->getInitials($name);

        return view('inscription.start', [
            'user' => $user,
            'displayName' => $displayName,
            'initials' => $initials
        ]);
    }

    public function show(): View|RedirectResponse
    {
        $user = Auth::user();

        $displayName = $user?->name && $user?->lgbt?->status === 'accepted' 
                    ? $user?->lgbt?->name 
                    : $user?->name;
        $name = $user?->name;
        $initials = $this->getInitials($name);
        
        // Segurança extra caso acessem direto sem ter inscrição
        if (! $user->inscription()->exists()) {
            return redirect()
                ->route('inscription.start')
                ->with('warning', 'Você ainda não possui inscrição ativa.');
        }

        // Carrega tudo que o painel precisa
        $user->load([
            'inscription.exam_result.examLocation',
            'inscription.exam_result.completedCall',
        ]);

        $inscription = $user->inscription;
        $examResult = $inscription->exam_result;
        $examLocation = $examResult?->examLocation;

        $exam = $examResult; // O EXAM REAL — o model completo

        $call = $examResult?->completedCall;

        return view('inscription.show', [
            'user' => $user,
            'displayName' => $displayName,
            'initials' => $initials,
            'exam' => $exam,
            'examResult' => $examResult,
            'examLocation' => $examLocation,
            'call' => $call
        ]);
    }

    private function getInitials(?string $name): string
    {
        if (!$name) return '';
        $parts = array_filter(explode(' ', trim($name)));
        return strtoupper(
            ($parts[0][0] ?? '') . 
            ($parts[1][0] ?? '')
        );
    }

    // Passo 1: Dados pessoais
    public function personal(): View|RedirectResponse
    {
        return view('inscription.steps.personal');
    }

    // Gravar Dados de Passo 1
    public function personalStore(PersonalRequest $request): RedirectResponse
    {
        $data = $request->except(['_token', 'authorization']);

        if (($data['social_name_option'] ?? null) == '2') {
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
        if (! session()->get('step1_done')) {
            return redirect()->route('inscription.step.personal')->with('warning', 'Complete o passo anterior.');
        }

        return view('inscription.steps.certificate');
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
        if (! session()->get('step2_done')) {
            return redirect()->route('inscription.step.certificate')->with('warning', 'Complete o passo anterior.');
        }

        return view('inscription.steps.address');
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
        if (! session()->get('step3_done')) {
            return redirect()->route('inscription.step.address')->with('warning', 'Complete o passo anterior.');
        }

        $schools = School::all();

        return view('inscription.steps.academic', [
            'schools' => $schools,
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
        if (! session()->get('step4_done')) {
            return redirect()->route('inscription.step.academic')->with('warning', 'Complete o passo anterior.');
        }

        $degrees = Degree::all();

        return view('inscription.steps.family', [
            'degrees' => $degrees,
        ]);
    }

    // Gravar Dados de Passo 5
    public function familyStore(FamilyRequest $request): Response|RedirectResponse
    {
        $data = $request->except(['_token']);

        if (data_get($data, 'degree') < 8) {
            $data['kinship'] = null;
        }

        if (data_get($data, 'respLegalOption') == '2') {
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
        if (! session()->get('step5_done')) {
            return redirect()->route('inscription.step.family')->with('warning', 'Complete o passo anterior.');
        }

        $disabilities = Disability::all();
        $accessibilityResources = Resource::all();
        $healthIssues = HealthIssue::all();

        return view('inscription.steps.others', compact('disabilities', 'accessibilityResources', 'healthIssues'));
    }

    // Gravar Dados de Passo 6
    public function otherStore(OtherRequest $request): Response|RedirectResponse
    {
        $data = $request->except(['_token', 'pne_report']);

        if (data_get($data, 'health') != 1) {
            $data['health_issue'] = null;
        }

        if ($request->hasFile('pne_report')) {
            $path = $request->file('pne_report')->store('reports', 'public');
            $data['pne_report'] = $path; // salva só o caminho
        }

        if (data_get($data, 'pne') != 1) {
            $data['accessibility_description'] = null;
            $data['pne_description'] = null;
        }

        if (data_get($data, 'social_program') != 1) {
            $data['nis'] = null;
        }

        session()->put('step6', $data);
        session()->put('step6_done', true); // Marca como concluído

        return redirect()->route('inscription.step.course');
    }

    // Passo 7: Curso Pretendido
    public function course(): View|RedirectResponse
    {
        if (! session()->get('step6_done')) {
            return redirect()->route('inscription.step.other')->with('warning', 'Complete o passo anterior.');
        }

        return view('inscription.steps.course', [
            'courses' => Course::all(),
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
            return redirect()->route('inscription.start'); // <= CORRIGIR ROTA
        }

        // Retorna a view com os dados necessários
        return view('inscription.steps.confirm', array_merge(
            $steps->all()
        ));
    }

    // Gravar Dados de Passo 8
    public function inscriptionStore(Request $request, InscriptionService $inscriptionService)
    {
        try {
            $request->validate([
                'agree_terms' => 'accepted',
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
            }

            if (app()->environment('local')) {
                throw $e;
            }

            return redirect()->route('failed')->with([
                'status' => [
                    'alert-type' => 'danger',
                    'message' => 'Erro ao salvar os dados. Verifique se todos os campos estão corretos.',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return redirect()->route('failed')->with(
                'danger',
                $e->getMessage() === 'Inscrição já realizada.'
                    ? 'Você já se inscreveu.'
                    : 'Erro inesperado. Por favor, tente novamente.',
            );
        }
    }
}