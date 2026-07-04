<?php

namespace App\Exports;

use App\Models\Call;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CallExport implements FromCollection, WithHeadings
{
    protected $call_number;

    public function __construct($call_number)
    {
        $this->call_number = $call_number;
    }

    public function collection()
    {
        return Call::with([
            'examResult.inscription.user.lgbt',
            'examResult.inscription.user.document',
            'examResult.inscription.user.certificate',
            'examResult.inscription.user.academic',
            'examResult.inscription.user.mother',
            'examResult.inscription.user.father',
            'examResult.inscription.user.guardian',
            'examResult.inscription.user.parent_email',
            'examResult.inscription.user.pne',
        ])
            ->where('call_number', $this->call_number)
            ->whereHas('callList', fn ($q) => $q->where('status', 'completed'))
            ->join('exam_results', 'calls.exam_result_id', '=', 'exam_results.id')
            ->orderBy('exam_results.ranking')
            ->select('calls.*')
            ->get()
            ->map(function ($call) {
                $user = $call->examResult->inscription->user;
                // $detail = $user->user_detail;

                return [
                    'Inscrição' => $call->examResult->inscription->id,
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
