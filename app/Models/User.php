<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;

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
        // Nascimento
        'birth',

        // Relacionamentos
        // Documento
        'document_id',
        // Nacionalidade
        'nationality_id',
        // Gênero
        'gender_id',

        // Email
        'email',
        'email_verified_at',

        // Autenticação
        'password',
        'token',
        'last_login_at'
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
            // 'birth' => 'date',
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relacionamentos

    public function social_name(): HasOne
    {
        return $this->hasOne(SocialName::class);
    }

    /**
     * Obter o documento do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    /**
     * Obter o gênero do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    /**
     * Obter os endereços do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    /**
     * Obter a nacionalidade do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    /**
     * Obter os telefones do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
    }

    /**
     * Obter a escola do usuário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function school(): HasOne
    {
        return $this->hasOne(School::class);
    }


    public function mother(): HasOne
    {
        return $this->hasOne(Mother::class);
    }

    public function father(): HasOne
    {
        return $this->hasOne(Father::class);
    }

    public function guardian(): HasOne
    {
        return $this->hasOne(Guardian::class);
    }

    public function social_program(): HasOne
    {
        return $this->hasOne(SocialProgram::class);
    }

    public function health(): HasOne
    {
        return $this->hasOne(Health::class);
    }

    public function pne(): HasOne
    {
        return $this->hasOne(Pne::class);
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

    /**
     * Verifica se o usuário possui uma inscrição.
     *
     * @return bool
     */
    public function hasInscription(): bool
    {
        return $this->inscription()->exists();
    }

    // Acessors
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
     * Retorna a data de nascimento formatada como 'd/m/Y' ou null se a data de nascimento for nula.
     *
     * @return string|null
     */
    public function getBirthFormattedAttribute(): ?string
    {
        return $this->birth ? $this->birth->format('d/m/Y') : null;
    }

    public function getBirthAttribute($value)
    {
        return Carbon::parse($value)->format('d/m/Y');
    }

    public function getAgeAttribute(): ?int
    {
        $birth = $this->getRawOriginal('birth');
        return $birth ? Carbon::parse($birth)->age : null;
    }

    // Mutators
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

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_users_without_inscription'));
        static::deleted(fn() => Cache::forget('global_users_without_inscription'));
    }
}
