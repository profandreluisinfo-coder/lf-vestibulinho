<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_details';

    protected $primaryKey = 'user_id';
    public $incrementing = false; // Não é auto-incremento
    protected $keyType = 'int'; // Ou 'string', se for UUID ou algo assim

    public $timestamps = false;

    protected $fillable = [
        'user_id',

        // Nacionalidade
        'nationality',

        // Documentos pessoais
        'doc_type',
        'doc_number',

        // Contato
        'phone',

        // Endereço
        'zip',
        'street',
        'number',
        'complement',
        'burgh',
        'city',
        'state',

        // Escola onde completou (completará) o ensino fundamental
        'school_name',
        'school_city',
        'school_state',
        'school_year',
        'school_ra',

        // Certidão de nascimento
        'new_number',
        'fls',
        'book',
        'old_number',
        'municipality',

        // Família
        'mother',
        'father',
        'mother_phone',
        'father_phone',
        'responsible',
        'responsible_phone',
        'degree',
        'kinship',
        'parents_email',

        // Outras informações
        'health',
        'accessibility',
        'nis',
    ];

    /**
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === "") {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Obtenha o usuário que possui o UserDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna a nacionalidade do usuário, baseada no valor do atributo 'nationality'.
     * 
     * @param int $value    O valor do atributo 'nationality'
     * @return string    A nacionalidade do usuário, 'BRASILEIRA' ou 'ESTRANGEIRA'
     */
    public function getNationalityAttribute($value)
    {
        return $value === '1' ? 'BRASILEIRA' : 'ESTRANGEIRA';
    }

    /**
     * Retorna o tipo de documento do usuário, baseado no valor do atributo 'doc_type'.
     * 
     * @param int $value    O valor do atributo 'doc_type'
     * @return string    O tipo de documento do usuário, 'RG - REGISTRO GERAL', 'CIN - CARTEIRA DE IDENTIDADE NACIONAL', 'RNE - REGISTRO NACIONAL DE ESTRANGEIRO' ou um valor desconhecido
     */
    public function getDocTypeAttribute($value)
    {
        switch ($value) {
            case '1':
                return 'RG - REGISTRO GERAL';
                break;
            case '2':
                return 'CIN - CARTEIRA DE IDENTIDADE NACIONAL';
                break;
            case '3':
                return 'RNE - REGISTRO NACIONAL DE ESTRANGEIRO';
                break;
            default:
                return $value . ' - ERRO AO IDENTIFICAR TIPO DE DOCUMENTO';
        }
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'doc_number'.
     *
     * @param string $value    O valor do atributo 'doc_number'
     */
    public function setDocNumberAttribute($value)
    {
        $this->attributes['doc_number'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'doc_number' formatado como 99.999.999-9.
     *
     * @param string $value    O valor do atributo 'doc_number'
     * @return string    O valor do atributo 'doc_number' formatado como 99.999.999-9
     */
    public function getDocNumberAttribute($value)
    {
        // Formato ao retornar deve ser 99.999.999-9
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{1})/', '$1.$2.$3-$4', $value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'phone'.
     *
     * @param string $value    O valor do atributo 'phone'
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace(['(', ')', ' ', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'phone' formatado como (99) 99999-9999.
     *
     * @param string $value    O valor do atributo 'phone'
     * @return string    O valor do atributo 'phone' formatado como (99) 99999-9999
     */
    public function getPhoneAttribute($value)
    {
        // return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value);
        return $this->formatPhone($value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'zip' (CEP).
     *
     * @param string $value    O valor do atributo 'zip'
     */
    public function setZipAttribute($value)
    {
        $this->attributes['zip'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'zip' (CEP) formatado como 99.999-99.
     *
     * @param string $value    O valor do atributo 'zip'
     * @return string    O valor do atributo 'zip' formatado como 99.999-99
     */
    public function getZipAttribute($value)
    {
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value);
    }

    /**
     * Converte o valor do atributo 'city' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'city'
     * @return $this
     */
    public function setCityAttribute($value)
    {
        $this->attributes['city'] = Str::of($value)->upper();;
    }

    /**
     * Converte o valor do atributo 'state' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'state'
     * @return $this
     */
    public function setStateAttribute($value)
    {
        $this->attributes['state'] = Str::of($value)->upper();;
    }

    /**
     * Retorna o valor do atributo 'degree' (Grau de Parentesco) formatado com o nome da formação de grau.
     *
     * @param string $value    O valor do atributo 'degree'
     * @return string    O valor do atributo 'degree' formatado com o nome da formação de grau.
     */
    public function getDegreeAttribute($value)
    {
        $degrees = [
            '1' => 'PADRASTO',
            '2' => 'MADRASTA',
            '3' => 'AVÔ(Ó)',
            '4' => 'TIO(A)',
            '5' => 'IRMÃO(Ã)',
            '6' => 'PRIMO(A)',
            '7' => 'TIO(A)',
            '8' => 'OUTRO',
        ];

        return $degrees[$value] ?? $value;  // Se não encontrar, retorna o valor original
    }

    /**
     * Converte o valor do atributo 'mother' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'mother'
     * @return $this
     */
    public function setMotherAttribute($value)
    {
        $this->attributes['mother'] = $this->toUpper($value);
    }

    /**
     * Converte o valor do atributo 'father' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'father'
     * @return $this
     */
    public function setFatherAttribute($value)
    {
        $this->attributes['father'] = $this->toUpper($value);
    }

    /**
     * Converte o valor do atributo 'responsible' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'responsible'
     * @return $this
     */
    public function setResponsibleAttribute($value)
    {
        $this->attributes['responsible'] = $this->toUpper($value);
    }

    /**
     * Converte o valor para maiúsculas, tratando nulos e espaços.
     *
     * @param  string|null  $value
     * @return string|null
     */
    private function toUpper(?string $value): ?string
    {
        return $value ? Str::of(trim($value))->upper() : null;
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'mother_phone'.
     *
     * @param string $value    O valor do atributo 'mother_phone'
     */
    public function setMotherPhoneAttribute($value)
    {
        $this->attributes['mother_phone'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Retorna o valor do atributo 'mother_phone' formatado como (99) 99999-9999.
     *
     * @param string $value    O valor do atributo 'mother_phone'
     * @return string    O valor do atributo 'mother_phone' formatado como (99) 99999-9999
     */
    public function getMotherPhoneAttribute($value)
    {
        return $this->formatPhone($value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'father_phone'.
     *
     * @param string $value    O valor do atributo 'father_phone'
     */
    public function setFatherPhoneAttribute($value)
    {
        $this->attributes['father_phone'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Retorna o valor do atributo 'father_phone' formatado como (99) 99999-9999.
     *
     * @param string $value    O valor do atributo 'father_phone'
     * @return string    O valor do atributo 'father_phone' formatado como (99) 99999-9999
     */
    public function getFatherPhoneAttribute($value)
    {
        return $this->formatPhone($value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'responsible_phone'.
     *
     * @param string $value    O valor do atributo 'responsible_phone'
     */
    public function setResponsiblePhoneAttribute($value)
    {
        $this->attributes['responsible_phone'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Retorna o valor do atributo 'responsible_phone' formatado como (99) 99999-9999.
     *
     * @param string $value    O valor do atributo 'responsible_phone'
     * @return string    O valor do atributo 'responsible_phone' formatado como (99) 99999-9999
     */
    public function getResponsiblePhoneAttribute($value)
    {
        return $this->formatPhone($value);
    }

    /**
     * Converte o valor do atributo 'fls' para maiúsculo.
     *
     * @param string $value    O valor do atributo 'fls'
     */
    public function setFlsAttribute($value)
    {
        $this->attributes['fls'] = strtoupper($value);
    }

    /**
     * Remove qualquer coisa que não seja número do atributo 'old_number'.
     *
     * @param string $value    O valor do atributo 'old_number'
     * @return void
     */
    public function setOldNumberAttribute($value)
    {
        // Remove qualquer coisa que não seja número
        $this->attributes['old_number'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Aplica o formato 99.99999 ao valor do atributo 'old_number'.
     *
     * @param string $value    O valor do atributo 'old_number'
     * @return string    O valor do atributo 'old_number' formatado como 99.99999
     */
    public function getOldNumberAttribute($value)
    {
        // Aplica o formato 99.99999
        return preg_replace('/^(\d{2})(\d{5})$/', '$1.$2', $value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'nis'.
     *
     * @param string $value    O valor do atributo 'nis'
     * @return void
     */
    public function setNisAttribute($value)
    {
        $this->attributes['nis'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'nis' formatado como 999.99999-9.
     *
     * @param string $value    O valor do atributo 'nis'
     * @return string    O valor do atributo 'nis' formatado como 999.99999-9
     */
    public function getNisAttribute($value)
    {
        return preg_replace('/(\d{3})(\d{5})(\d{2})(\d{1})/', '$1.$2.$3-$4', $value);
    }

    /**
     * Retorna o valor do atributo 'school_ra' formatado como XXX.XXX.XXX-Y.
     * O formato aceita 9 dígitos seguidos de 1 letra ou número.
     * Se o valor não seguir o formato, ele será retornado sem alterações.
     *
     * @param string $value    O valor do atributo 'school_ra'
     * @return string    O valor do atributo 'school_ra' formatado como XXX.XXX.XXX-Y
     */
    public function getSchoolRaAttribute($value)
    {
        // Aceita 9 dígitos seguidos de 1 letra ou número
        if (preg_match('/^(\d{9})([A-Za-z0-9])$/', $value, $matches)) {
            return substr($matches[1], 0, 3) . '.' .
                substr($matches[1], 3, 3) . '.' .
                substr($matches[1], 6, 3) . '-' .
                $matches[2];
        }

        return $value;
    }

    /**
     * Remove apenas pontos e traços do valor do atributo 'school_ra'.
     *
     * @param string $value    O valor do atributo 'school_ra'
     * @return void
     */
    public function setSchoolRaAttribute($value)
    {
        // Remove apenas pontos e traços
        $cleaned = str_replace(['.', '-'], '', $value);

        $this->attributes['school_ra'] = $cleaned;
    }

    /**
     * Formata o valor do atributo 'telefone' no formato (XX) XXXX-XXXX.
     * O formato aceita 11 dígitos seguidos de 1 letra ou número.
     * Se o valor não seguir o formato, ele será retornado sem alterações.
     *
     * @param string $value    O valor do atributo 'telefone'
     * @return string    O valor do atributo 'telefone' formatado como (XX) XXXX-XXXX
     */
    private function formatPhone($value)
    {
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value);
    }
}