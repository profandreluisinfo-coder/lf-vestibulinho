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
        return Call::with('examResult.inscription.user.user_detail')
            ->where('call_number', $this->call_number)
            ->whereHas('callList', fn($q) => $q->where('status', 'completed'))
            ->join('exam_results', 'calls.exam_result_id', '=', 'exam_results.id')
            ->orderBy('exam_results.ranking')
            ->select('calls.*')
            ->get()
            ->map(function ($call) {
                $user = $call->examResult->inscription->user;
                $detail = $user->user_detail;

                return [
                    'Inscrição'        => $call->examResult->inscription->id,
                    'Nome'             => $user->name,
                    'Nome Social'      => $user->social_name,
                    'CPF'              => $user->cpf,
                    'Nascimento'       => \Carbon\Carbon::parse($user->birth)->format('d/m/Y'),
                    'Gênero'           => $user->gender,
                    'E-mail'           => $user->email,

                    // user_details
                    'Nacionalidade'    => $detail?->nationality,
                    'Documento'        => $detail?->doc_type,
                    'Nº Documento'     => $detail?->doc_number,
                    'Telefone'         => $detail?->phone,

                    // Endereço
                    'CEP'              => $detail?->zip,
                    'Rua/Avenida'      => $detail?->street,
                    'Nº'               => $detail?->number,
                    'Complemento'      => $detail?->complement,
                    'Bairro'           => $detail?->burgh,
                    'Cidade'           => $detail?->city,
                    'Estado'           => $detail?->state,

                    // Certidão de nascimento
                    'Certidão Nova'    => $detail?->new_number,
                    'Certidão Antiga'  => $detail?->old_number,
                    'Folhas'           => $detail?->fls,
                    'Livro'            => $detail?->book,
                    'Município'        => $detail?->municipality,

                    // Escola
                    'Escola'           => $detail?->school_name,
                    'Cidade Escola'    => $detail?->school_city,
                    'UF Escola'        => $detail?->school_state,
                    'Ano Conclusão'    => $detail?->school_year,
                    'RA Escolar'       => $detail?->school_ra,

                    // Responsável
                    'Mãe'              => $detail?->mother,
                    'Telefone Mãe'     => $detail?->mother_phone,
                    'Pai'              => $detail?->father,
                    'Telefone Pai'     => $detail?->father_phone,
                    'Responsável'      => $detail?->responsible,
                    'Parentesco'       => $detail?->degree,
                    'Outro Parentesco' => $detail?->kinship,
                    'Tel Resp'    => $detail?->responsible_phone,
                    'E-mail Pais'      => $detail?->parents_email,

                    // Saúde
                    'Alérgico'         => $detail?->health ? 'Sim' : 'Não',
                    'Alergia'          => $detail?->health,
                    'Educação Especial'=> $user->pne ? 'Sim' : 'Não',
                    'Descrição'        => $detail?->accessibility,

                    // Programas sociais
                    'Bolsa Família'     => $detail?->nis ? 'Sim' : 'Não',
                    'NIS'              => $detail?->nis,
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