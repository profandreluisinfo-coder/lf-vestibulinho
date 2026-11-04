<?php

namespace App\Exports;

use App\Models\Call;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallExport implements FromCollection, WithHeadings
{
    protected $call_number;
    protected $processId;

    public function __construct($call_number, ?int $processId = null)
    {
        $this->call_number = $call_number;
        $this->processId = $processId;
    }

    public function collection()
    {
        return Call::with([
            'result.inscription.user.lgbt',
            'result.inscription.user.document',
            'result.inscription.user.certificate',
            'result.inscription.user.academic',
            'result.inscription.user.mother',
            'result.inscription.user.father',
            'result.inscription.user.guardian',
            'result.inscription.user.parent_email',
            'result.inscription.user.pne',
        ])
            ->where('call_number', $this->call_number)
            ->whereHas('callList', fn ($q) => $q
                ->where('status', 'completed')
                ->when($this->processId, fn ($query) => $query->where('process_id', $this->processId)))
            ->whereHas('result', fn ($query) => $query->where('classified', true))
            ->join('results', 'calls.result_id', '=', 'results.id')
            ->orderBy('results.ranking')
            ->select('calls.*')
            ->get()
            ->map(function ($call) {
                $user = $call->result->inscription->user;
                // $detail = $user->user_detail;

                return [
                    'Inscrição' => $call->result->inscription->id,
                    'Nome' => $user->name,
                    'Nome Social' => $user?->lgbt?->name,
                    'CPF' => $user->cpf,
                    'Nascimento' => $user->birth->format('d/m/Y'),
                    'Gênero' => $user->gender,
                    'E-mail' => $user->email,

                    // user_details
                    'Nacionalidade' => $user->nationality,
                    'Documento' => $user->document->type,
                    'Nº Documento' => $user->document->number,
                    'Telefone' => $user->phone,

                    // Endereço
                    'CEP' => $user->zip,
                    'Rua/Avenida' => $user->street,
                    'Nº' => $user->number,
                    'Complemento' => $user?->complement ?? '',
                    'Bairro' => $user->burgh,
                    'Cidade' => $user->city,
                    'Estado' => $user->state,

                    // Certidão de nascimento
                    'Certidão de Nascimento' => $user?->certificate?->number,
                    'Folhas' => $user?->certificate?->fls ?? '-',
                    'Livro' => $user?->certificate?->book ?? '-',
                    'Município' => $user?->certificate?->city ?? '-',

                    // Escola
                    'Escola' => $user?->academic?->school,
                    'Cidade Escola' => $user?->academic?->city,
                    'UF Escola' => $user?->academic?->state,
                    'Ano Conclusão' => $user?->academic?->year,
                    'RA Escolar' => $user?->academic?->ra,

                    // Responsável
                    'Mãe' => $user->mother->name,
                    'Telefone Mãe' => $user?->mother?->phone,
                    'Pai' => $user?->father?->name,
                    'Telefone Pai' => $user?->father?->phone,
                    'Responsável' => $user?->guardian?->name,
                    'Parentesco' => $user?->guardian?->degree,
                    'Outro Parentesco' =>  $user?->guardian?->kinship,
                    'Tel Resp' =>  $user?->guardian?->phone,
                    'E-mail Pais' => $user->parent_email->address,

                    // Saúde
                    'Alérgico' => $user?->health_issue ? 'Sim' : 'Não',
                    'Alergia' => $user?->health_issue ?? '-',
                    'Educação Especial' => $user->pne ? 'Sim' : 'Não',
                    'Descrição' => $user?->pne?->description ?? '-',

                    // Programas sociais
                    'Bolsa Família' => $user?->nis ? 'Sim' : 'Não',
                    'NIS' => $user?->nis ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Inscrição',
            'Nome',
            'Nome Social',
            'CPF',
            'Nascimento',
            'Gênero',
            'E-mail',

            'Nacionalidade',
            'Documento',
            'Nº Documento',
            'Telefone',

            'CEP',
            'Rua/Avenida',
            'Nº',
            'Complemento',
            'Bairro',
            'Cidade',
            'Estado',

            'Certidão Nova',
            'Certidão Antiga',
            'Folhas',
            'Livro',
            'Município',

            'Escola',
            'Cidade Escola',
            'UF Escola',
            'Ano Conclusão',
            'RA Escolar',

            'Mãe',
            'Telefone Mãe',
            'Pai',
            'Telefone Pai',
            'Responsável',
            'Grau Parentesco',
            'Outro Parentesco',
            'Tel Resp',
            'E-mail Pais',

            'Alérgico',
            'Alergia',
            'Educação Especial',
            'Descrição',

            'Bolsa Família',
            'NIS',
        ];
    }
}
