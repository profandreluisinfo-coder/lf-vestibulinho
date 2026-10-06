<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Grupos de campos que pertencem a OUTRAS tabelas (não à tabela users).
     * Usamos esta lista para separar o que vai para users do que vai para as relações.
     */
    private const RELATIONS = [
        'document',
        'lgbt',
        'certificate',
        'academic',
        'mother',
        'father',
        'guardian',
        'parent_email',
        'pne',
    ];

    /**
     * Página principal do painel de administração contendo a lista de usuários sem inscrição.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $user = User::find($id);

        return view('admin.users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $user->load(['inscription.course', 'lgbt', 'document', 'certificate', 'mother', 'father', 'guardian', 'parent_email', 'academic', 'pne']);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Atualiza o candidato e distribui cada dado na sua tabela,
     * da mesma forma que o InscriptionService faz no cadastro.
     *
     * No formulário (edit.blade.php), os campos das outras tabelas
     * devem usar nome em formato de grupo. Exemplos:
     *   name="document[type]"   name="mother[name]"   name="academic[school]"
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $user->load(['document', 'certificate']);

        $this->keepOnlyDigits($request, [
            'cpf',
            'nis',
            'zip',
            'phone',
            'mother.phone',
            'father.phone',
            'guardian.phone',
            'document.number',
            'certificate.number',
            'academic.ra',
        ]);

        $validated = $request->validate([

            // ---------- Tabela: users (Identificação, Endereço, NIS, Saúde) ----------
            'cpf' => ['required', 'string', 'digits:11', Rule::unique('users', 'cpf')->ignore($user->id)],
            'name' => ['string', 'max:100'],
            'birth' => ['required', 'date'],
            'gender' => ['required', Rule::in(array_keys(User::GENDERS))],
            'nationality' => ['required', Rule::in(['1', '2', '3', '4'])],
            'phone' => ['required', 'digits_between:10,11'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($user->id)],
            'zip' => ['required', 'string', 'max:8'],
            'street' => ['required', 'string', 'max:60'],
            'home' => ['required', 'string', 'max:10'],
            'complement' => ['nullable', 'string', 'max:45'],
            'burgh' => ['required', 'string', 'max:45'],
            'city' => ['required', 'string', 'max:45'],
            'state' => ['required', 'string', 'max:45'],
            'nis' => ['nullable', 'string', 'digits:11'],
            'health_issue' => ['nullable', 'string', 'max:100'],

            // ---------- Tabela: documents (Documentos Pessoais) ----------
            'document.type' => ['required', 'integer'],
            'document.number' => ['required', 'string', 'max:15', Rule::unique('documents', 'number')->ignore($user->document?->id)],
            'document.expedition' => ['required', 'date'],

            // ---------- Tabela: certificates (Certidão de Nascimento) ----------
            'certificate.type' => ['required', Rule::in(['1', '2'])], // 1 = Novo, 2 = Antigo
            'certificate.number' => ['required', 'string', 'max:32', Rule::unique('certificates', 'number')->ignore($user->certificate?->id)],
            'certificate.fls' => ['nullable', 'string', 'max:10'],
            'certificate.book' => ['nullable', 'string', 'max:10'],
            'certificate.city' => ['nullable', 'string', 'max:45'],

            // ---------- Tabela: academics (Escolaridade) ----------
            'academic.school' => ['required', 'string', 'max:100'],
            'academic.city' => ['required', 'string', 'max:45'],
            'academic.state' => ['required', 'string', 'size:2'],
            'academic.year' => ['required', 'digits:4'],
            'academic.ra' => ['required', 'string', 'max:20'],

            // ---------- Tabelas: mothers, fathers, guardians, parent_emails (Família) ----------
            'mother.name' => ['nullable', 'string', 'max:255'],
            'mother.phone' => ['nullable', 'digits_between:10,11'],

            'father.name' => ['nullable', 'string', 'max:255'],
            'father.phone' => ['nullable', 'digits_between:10,11'],

            'guardian.name' => ['nullable', 'string', 'max:255'],
            'guardian.phone' => ['nullable', 'digits_between:10,11'],
            'guardian.degree' => ['nullable', 'string', 'max:45'],
            'guardian.kinship' => ['nullable', 'string', 'max:45'],

            'parent_email.address' => ['required', 'email', 'max:255'],

            // ---------- Tabela: lgbts (Nome Social) ----------
            'lgbt.name' => ['nullable', 'string', 'max:100'],
            'lgbt.status' => ['nullable', Rule::in(['pending', 'accepted', 'rejected'])],
            'lgbt.observations' => ['nullable', 'string', 'max:255'],

            // ---------- Tabela: pnes (Educação Especial) ----------
            'pne.description' => ['nullable', 'string'],
            'pne.support' => ['nullable', 'string', 'max:255'],
            'pne.status' => ['nullable', Rule::in(['pending', 'accepted', 'rejected'])],
            'pne.observations' => ['nullable', 'string', 'max:255'],
        ]);

        // Tudo dentro de uma transação: ou salva tudo, ou não salva nada.
        DB::transaction(function () use ($user, $validated) {

            // 1) users: tudo que NÃO é de outra tabela
            $user->update(Arr::except($validated, self::RELATIONS));

            // 2) documents
            if (isset($validated['document'])) {
                $doc = $validated['document'];

                $user->document()->updateOrCreate([], [
                    'type' => data_get($doc, 'type'),
                    'number' => data_get($doc, 'number'),
                    'expedition' => data_get($doc, 'expedition'),
                ]);
            }

            // 3) certificates (certidão nova, tipo 1, não tem folhas/livro/município)
            if (isset($validated['certificate'])) {
                $cert = $validated['certificate'];
                $isNew = (string) data_get($cert, 'type') === '1';

                $user->certificate()->updateOrCreate([], [
                    'type' => data_get($cert, 'type'),
                    'number' => data_get($cert, 'number'),
                    'fls' => $isNew ? null : data_get($cert, 'fls'),
                    'book' => $isNew ? null : data_get($cert, 'book'),
                    'city' => $isNew ? null : data_get($cert, 'city'),
                ]);
            }

            // 4) academics
            if (isset($validated['academic'])) {
                $academic = $validated['academic'];

                $user->academic()->updateOrCreate([], [
                    'school' => data_get($academic, 'school'),
                    'city' => data_get($academic, 'city'),
                    'state' => data_get($academic, 'state'),
                    'year' => data_get($academic, 'year'),
                    'ra' => data_get($academic, 'ra'),
                ]);
            }

            // 5) mothers
            if (isset($validated['mother'])) {
                $user->mother()->updateOrCreate([], [
                    'name' => data_get($validated, 'mother.name'),
                    'phone' => data_get($validated, 'mother.phone'),
                ]);
            }

            // 6) fathers: só existe se o nome foi preenchido (igual ao Service)
            if (array_key_exists('father', $validated)) {
                if (! empty(data_get($validated, 'father.name'))) {
                    $user->father()->updateOrCreate([], [
                        'name' => data_get($validated, 'father.name'),
                        'phone' => data_get($validated, 'father.phone'),
                    ]);
                } else {
                    $user->father()->delete();
                }
            }

            // 7) guardians: só existe se o nome foi preenchido
            //    'kinship' só é usado quando degree == '8' (Outro), igual ao Service
            if (array_key_exists('guardian', $validated)) {
                if (! empty(data_get($validated, 'guardian.name'))) {
                    $degree = data_get($validated, 'guardian.degree');

                    $user->guardian()->updateOrCreate([], [
                        'name' => data_get($validated, 'guardian.name'),
                        'phone' => data_get($validated, 'guardian.phone'),
                        'degree' => $degree,
                        'kinship' => ($degree == '8' && ! empty(data_get($validated, 'guardian.kinship')))
                            ? data_get($validated, 'guardian.kinship')
                            : '',
                    ]);
                } else {
                    $user->guardian()->delete();
                }
            }

            // 8) parent_emails
            if (isset($validated['parent_email'])) {
                $user->parent_email()->updateOrCreate([], [
                    'address' => data_get($validated, 'parent_email.address'),
                ]);
            }

            // 9) lgbts: o admin só ATUALIZA se o candidato pediu nome social
            if (isset($validated['lgbt']) && $user->lgbt) {
                $user->lgbt()->update([
                    'name' => data_get($validated, 'lgbt.name'),
                    'status' => data_get($validated, 'lgbt.status', $user->lgbt->status),
                    'observations' => data_get($validated, 'lgbt.observations'),
                ]);
            }

            // 10) pnes: o admin só ATUALIZA se o candidato declarou PNE
            if (isset($validated['pne']) && $user->pne) {
                $user->pne()->update([
                    'description' => data_get($validated, 'pne.description'),
                    'support' => data_get($validated, 'pne.support'),
                    'status' => data_get($validated, 'pne.status', $user->pne->status),
                    'observations' => data_get($validated, 'pne.observations'),
                ]);
            }
        });

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Dados do candidato atualizados com sucesso.');
    }

    /**
     * Deixa só os números nos campos que chegam com máscara.
     * Roda ANTES do validate(), para o max:11 enxergar só os dígitos.
     */
    private function keepOnlyDigits(Request $request, array $fields): void
    {
        $data = $request->all();

        foreach ($fields as $field) {
            $value = data_get($data, $field);

            if (filled($value)) {
                data_set($data, $field, preg_replace('/\D/', '', $value));
            }
        }

        $request->merge($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->role !== 'user') {
            return alertError('Este usuário não pode ser excluído.');
        }

        // Verificar se o usuário possui inscrição associada
        if ($user?->inscription) {
            return alertError('Este usuário possui uma inscrição associada e não pode ser excluído.');
        }

        $user->delete();

        return alertSuccess(
            'Usuário excluído com sucesso!',
            'admin.users.index'
        );
    }
}