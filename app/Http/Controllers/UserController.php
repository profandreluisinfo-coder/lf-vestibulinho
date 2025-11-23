<?php

namespace App\Http\Controllers;

use App\Models\Call;
use App\Models\User;
use Illuminate\View\View;
use App\Models\ExamResult;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use App\Models\Calendar;

class UserController extends Controller
{
/**
 * Exibe a lista de candidatos inscritos (inclusive candidatos com deficiência).
 *
 * @return View
 */
    public function index(): View
    {
        $users = User::getWithoutInscription();

        view()->share('users', $users);

        return view('user.registered.index');
    }

    /**
     * Mostra a página de registro de dados de acesso para o usuário.
     *
     * Caso o calendário do Processo Seletivo esteja aberto, a página de registro será exibida.
     * Caso contrário, o usuário será redirecionado para a página de início.
     *
     * @return View|RedirectResponse
     */
    public function register(): View | RedirectResponse
    {
        $calendar = Calendar::first();

        if (!$calendar->isInscriptionOpen()) {
            return redirect()->route('home');
        }

        return view('auth.user.register');
    }

    /**
     * Realiza o registro do usuário com base nas credenciais informadas.
     *
     * Valida os campos 'email', 'password', 'password_confirmation' e 'terms' e
     * tenta registrar o usuário com base nas credenciais informadas.
     * Se o registro for bem sucedido, o usuário receberá um e-mail para confirmar o endereço de e-mail.
     * Caso contrário, será exibido um erro.
     *
     * @param Request $request
     * @param UserService $userService
     * @return RedirectResponse|View
     */
    public function store(Request $request, UserService $userService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{6,8}$/',
            'password_confirmation' => 'required',
            'terms' => 'required'
        ], [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email deve ser um email válido',
            'password.required' => 'O campo senha é obrigatório',
            'password.confirmed' => 'As senhas devem ser iguais',
            'password.regex' => 'A senha deve ter de 6 a 8 caracteres, com pelo menos uma letra maiúscula, uma minúscula e um número.',
            'password_confirmation.required' => 'O campo repetir senha é obrigatório',
            'terms.required' => 'É necessário aceitar os termos do edital.'
        ]);

        $result = $userService->register($credentials);

        if (!$result['success']) {
            return redirect()->route('register')->with('error', $result['message']);
        }

        return view('messages.email-sent', [
            'email' => $result['user']->email
        ]);
    }
    
    // Método para obtenção dos dados do local de realização de prova do candidato
    public function showExamLocation(): View
    {
        $user = auth()->user();

        $exam = DB::table('exam_results')
            ->join('exam_locations', 'exam_results.exam_location_id', '=', 'exam_locations.id')
            ->join('inscriptions', 'exam_results.inscription_id', '=', 'inscriptions.id')
            ->where('inscriptions.user_id', $user->id)
            ->select(
                'exam_locations.name as location_name',
                'exam_results.room_number',
                'exam_results.exam_date',
                'exam_results.exam_time',
                'user_id',
                'users.pne'
            )
            ->join('users', 'inscriptions.user_id', '=', 'users.id')
            ->first();

        return view('user.exam-location', compact('exam'));
    }

    public function examCardPdf()
    {
        $user = Auth::user();

        $exam = ExamResult::with('location')
            ->whereHas('inscription', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$exam) {
            return redirect()->back()->with('error', 'Local de prova não encontrado.');
        }

        $pdf = Pdf::loadView('user.pdf.exam-card', compact('exam'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('cartao-local-prova.pdf');
    }

    public function resultCardPdf()
    {
        $user = auth()->user();

        $examResult = ExamResult::with(['inscription.user'])
            ->whereHas('inscription', fn($q) => $q->where('user_id', $user->id))
            ->first();

        if (!$examResult) {
            return redirect()->route('user.results')->withErrors(['error' => 'Resultado ainda não disponível.']);
        }

        return Pdf::loadView('user.pdf.result-card', compact('examResult', 'user'))
            ->setPaper('a4', 'portrait')
            ->download('resultado-prova.pdf');
    }

    public function generateCallPdf()
    {
        $user = auth()->user();

        // Acessar a inscrição e o resultado do exame do usuário autenticado
        $examResult = $user->inscription->exam_result ?? null;

        if (!$examResult) {
            return null; // Usuário sem resultado de exame
        }

        // Verifica se há chamada para esse resultado com lista finalizada
        $call = Call::where('exam_result_id', $examResult->id)
            ->whereHas('callList', fn($query) => $query->where('status', 'completed'))
            ->with('callList') // carrega os dados da lista de chamada
            ->first();

        if (!$call) {
            return back()->with('warning', 'Nenhuma convocação finalizada encontrada.');
        }

        $pdf = Pdf::loadView('user.pdf.call_invitation', [
            'user' => $user,
            'call' => $call,
            'location' => 'R. Geraldo de Souza, 157/221 - Jardim Sao Carlos, Sumaré - SP, 13170-232',
            'phone' => '(19) 3873-2605',
            'open_hours' => 'Aberto ⋅ Fecha às 23:00',
            'documents' => [
                'Original e 01 cópia do Histórico Escolar do Ensino Fundamental e/ou Declaração de Conclusão;',
                'Original e 01 cópia do CPF;',
                'Original e 01 cópia do RG;',
                'Original e 01 cópia do comprovante de residência em Sumaré (menos de 30 dias);',
                'Original e 01 cópia da certidão de nascimento;',
                '3 (três) fotos 3x4;',
                'Laudo médico atualizado (se PCD).'
            ],
        ]);

        return $pdf->download('convocacao.pdf');
    }

    public function clearSocialName(User $user)
    {
        if (ExamResult::exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível apagar o nome social, pois existe prova agendada!'
            ]);
        }

        $user->update(['social_name' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Nome social apagado com sucesso!'
        ]);
    }

    public function clearPne(User $user)
    {
        if (ExamResult::exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível remover este candidato desta lista, pois existe prova agendada!'
            ]);
        }

        $user->update(['pne' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Candidato removido da list com sucesso!'
        ]);
    }
}