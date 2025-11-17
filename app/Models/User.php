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
        // Dados pessoais
        'cpf',
        'name',
        'social_name',
        // Gênero
        'gender',
        // Data de Nascimento
        'birth',
        'pne',
        'laudo',
        // Contato
        'email',
        // Token
        'token',
        // 'token_expires_at',
        'email_verified_at',

        // Dados de login
        'last_login_at',
        'password',
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
     * Obter o detalhe do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user_detail(): HasOne // user_detail
    {
        return $this->hasOne(UserDetail::class);
    }
    /**
     * Verifica se o usuário já foi convocado em alguma chamada finalizada.
     *
     * @return bool
     */
    public function hasConfirmedCall(): bool
    {
        $examResult = $this->inscription->exam_result ?? null;

        if (!$examResult) {
            return false;
        }

        return Call::where('exam_result_id', $examResult->id)
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
