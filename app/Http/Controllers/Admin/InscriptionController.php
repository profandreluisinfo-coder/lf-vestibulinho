<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inscription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class InscriptionController extends Controller
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
     * Exibe a lista de candidatos com inscrição ativa.
     *
     * @return View
     */
    public function index(): View
    {
        // Agora apenas retorna a view, os dados virão via AJAX
        return view('admin.inscriptions.index');
    }

    /**
     * Retorna os dados paginados para o DataTables via AJAX
     *
     * Os dados serão exibidos na tabela de inscrições ativas no dashboard, na view "inscriptions.index".
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        $draw              = $request->input('draw');
        $start             = $request->input('start');
        $length            = $request->input('length');
        $searchValue       = $request->input('search.value');
        $orderColumnIndex  = $request->input('order.0.column', 0);
        $orderDir          = $request->input('order.0.dir', 'asc');

        // Mapeamento de colunas para ordenação
        $columns     = ['inscriptions.id', 'users.name', 'users.cpf', 'processes.year'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'inscriptions.id';

        // ─────────────────────────────────────────────────────────
        // 1) Query BASE (sem joins) — usada somente para contagem
        // ─────────────────────────────────────────────────────────
        $baseQuery = User::whereHas('inscription');

        // Aplica a busca na base (para o totalFiltered respeitar o filtro)
        if (!empty($searchValue)) {
            $baseQuery->where(function ($q) use ($searchValue) {
                $q->where('users.name', 'like', "%{$searchValue}%")
                    ->orWhere('users.cpf', 'like', "%{$searchValue}%")
                    ->orWhereHas('inscription', function ($qi) use ($searchValue) {
                        $qi->where('inscriptions.id', 'like', "%{$searchValue}%");
                    })
                    ->orWhereHas('inscription.process', function ($qp) use ($searchValue) {
                        $qp->where('processes.year', 'like', "%{$searchValue}%");
                    });
            });
        }

        // Totais — como $baseQuery NÃO tem join, o COUNT(*) nunca duplica.
        // (clone garante que o count não "consuma" a query para o próximo uso)
        $totalRecords  = User::whereHas('inscription')->count();
        $totalFiltered = (clone $baseQuery)->count();

        // ─────────────────────────────────────────────────────────
        // 2) Query de DADOS — clona a base e adiciona joins + select
        // ─────────────────────────────────────────────────────────
        $query = (clone $baseQuery)
            ->with(['inscription.process'])
            ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
            ->leftJoin('processes', 'inscriptions.process_id', '=', 'processes.id')
            ->select(
                'users.*',
                'inscriptions.id as inscription_id',
                'processes.year as process_year'
            );

        $users = $query->orderBy($orderColumn, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // ─────────────────────────────────────────────────────────
        // 3) Formatação para o DataTables
        // ─────────────────────────────────────────────────────────
        $data = $users->map(function ($user) {
            $name = ($user?->lgbt && $user?->lgbt?->status == 'accepted')
                ? $user?->lgbt?->name . ' <i class="bi bi-gender-trans text-success" data-bs-toggle="popover" data-bs-trigger="hover"
                                data-bs-content="LGBTQIA+"></i>'
                : $user->name;

            return [
                'inscription_id' => $user->inscription?->id ?? $user->inscription_id,
                'name'           => $name,
                'cpf'            => $user->cpf,
                'process_year'   => $user->process_year,
                'user_id'        => $user->id,
                'actions'        => view('admin.inscriptions.inscription-actions', compact('user'))->render()
            ];
        });

        return response()->json([
            'draw'            => intval($draw),
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data'            => $data
        ]);
    }

    /**
     * Exibe a ficha de inscrição de um candidato especificado.
     *
     * @param string $id O ID do candidato.
     *
     * @return View A view com a ficha de inscrição do candidato.
     */
    public function show(User $user): View
    {
        // $user = User::find($id);

        return view('admin.inscriptions.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $user->load(['inscription.course', 'lgbt', 'document', 'certificate', 'mother', 'father', 'guardian', 'parent_email', 'academic', 'pne']);

        return view('admin.inscriptions.edit', compact('user'));
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

        return redirect()->route('admin.inscriptions.show', $user->id)
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
     * Candidatos com deficiência (PcD).
     */
    public function pcds(): View
    {
        return view('admin.inscriptions.pcds', [
            'users' => $this->candidatesWith('pne', 'pne'),
        ]);
    }

    /**
     * Candidatos com solicitação de nome social.
     */
    public function lgbts(): View
    {
        return view('admin.inscriptions.lgbts', [
            'users' => $this->candidatesWith('lgbt', 'lgbt'),
        ]);
    }

    /**
     * Usuários com inscrição e com a relação informada, já com tudo que a view usa.
     *
     * @param  string  $relation  Relação principal (pne ou lgbt)
     * @param  string  $appealType  Tipo de recurso correspondente
     */
    private function candidatesWith(string $relation, string $appealType): Collection
    {
        return User::whereHas($relation)
            ->whereHas('inscription')
            ->with([
                'inscription',
                $relation,
                'lgbt', // a view de PcD mostra o nome social quando deferido
                'appeals' => fn($query) => $query->where('type', $appealType),
            ])
            ->get();
    }

    // public function resources(): View
    // {
    //     $users = User::whereHas('inscription')->get();

    //     return view('admin.inscriptions.resources', [
    //         'users' => $users
    //     ]);
    // }

    /**
     * Exporta a lista de inscrições em formato PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {
        $search = $request->get('search');

        $users = User::whereHas('inscription')
            ->with('inscription')
            ->join('inscriptions', 'users.id', '=', 'inscriptions.user_id')
            ->select('users.*')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('users.name', 'like', "%{$search}%")
                        ->orWhere('users.cpf', 'like', "%{$search}%")
                        ->orWhere('inscriptions.id', 'like', "%{$search}%");
                });
            })
            ->orderBy('inscriptions.id', 'asc')
            ->get();

        if ($users->isEmpty()) {
            return redirect()->back()->with('warning', 'Nenhuma inscrição encontrada para os critérios informados.');
        }

        // código de geração de PDF...
        $pdf = Pdf::loadView('admin.inscriptions.pdf', [
            'users' => $users,
            'search' => $search,
        ]);

        return $pdf->download('inscricoes.pdf');
    }

}
