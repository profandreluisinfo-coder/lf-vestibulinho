<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory; // traits

    /**
     * Os atributos que são atribuíveis (definidos) em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        // Dados de login
        // Email
        'email',
        'email_verified_at',

        // Senha
        'password',

        // Token
        'token',

        'last_login_at',

        // Dados pessoais
        'cpf',
        'name',
        'social_name',

        // Data de Nascimento
        'birth',
        // Gênero
        'gender',
        // Nacionalidade
        'nationality',
        // Documentos pessoais
        'doc_type',
        'doc_number',

        // Certidão de Nascimento (modelo novo com 32 dígitos)
        'new_number',

        // Certidão de nascimento antiga
        'fls',
        'book',
        'old_number',
        'municipality',

        // Deficiência: 0 = Não, 1 = Sim
        'pne',
        'pne_description', // Educação Especial
        'pne_report_path', // caminho do arquivo do laudo

        // Alergia
        'health',

        // Programas sociais
        'nis',

        // Filiação
        'mother_name',
        'mother_phone',

        'father_name',
        'father_phone',

        // Responsável legal (caso não seja pai ou mãe)
        'guardian_name',
        'guardian_relationship',
        'guardian_phone',
        'guardian_email',

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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * Os atributos que devem ser convertidos em tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'pne' => 'boolean',
            'email_verified_at' => 'datetime',
            'birth' => 'date',
            'last_login_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Obter os arquivos de provas do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }

    /**
     * Obter as perguntas frequentemente do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    /**
     * Obter a inscrição do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inscription(): HasOne
    {
        return $this->hasOne(Inscription::class);
    }

    /**
     * Retornar todos os usuários sem inscrição e com role = 'user'.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getWithoutInscription()
    {
        return static::where('role', 'user')
            ->doesntHave('inscription')
            ->get();
    }

    /**
     * Verifica se o usuário já foi convocado em alguma chamada finalizada.
     *
     * @return bool
     */
    public function hasConfirmedCall(): bool
    {
        $result = $this->inscription->exam_result ?? null;

        if (!$result) {
            return false;
        }

        return Call::where('exam_result_id', $result->id)
            ->whereHas('callList', fn($query) => $query->where('status', 'completed'))
            ->exists();
    }

    // Getters e Setters
    public function getGenderAttribute($value)
    {
        $genders = [
            '1' => 'MASCULINO',
            '2' => 'FEMININO',
            '3' => 'OUTRO',
            '4' => 'PREFIRO NÃO INFORMAR'
        ];

        return $genders[$value] ?? $value;  // Se não encontrar, retorna o valor original
    }

    /**
     * Remove todos os caracteres não numéricos do CPF.
     *
     * @param string $value
     */
    public function setCpfAttribute($value)
    {
        // Remove todos os caracteres não numéricos
        $this->attributes['cpf'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Formata o CPF no formato 'xxx.xxx.xxx-xx'
     *
     * @param string $value
     * @return string
     */
    public function getCpfAttribute($value)
    {
        // Formata o CPF no formato 'xxx.xxx.xxx-xx'
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
    }

    /**
     * Retorna a data de nascimento formatada como 'd/m/Y' ou null se a data de nascimento for nula.
     *
     * @return string|null
     */
    public function getBirthFormattedAttribute()
    {
        return $this->birth ? $this->birth->format('d/m/Y') : null;
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
     * Retorna a idade do usuário, baseada na data de nascimento, ou null se a data de nascimento for nula.
     *
     * @return int|null
     */
    public function getAgeAttribute()
    {
        return $this->birth ? $this->birth->age : null;
    }

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_users_without_inscription'));
        static::deleted(fn() => Cache::forget('global_users_without_inscription'));
    }
}
