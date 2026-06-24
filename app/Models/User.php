<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory; // traits

    /**
     * Os atributos que são atribuíveis (definidos) em massa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'cpf',
        'name',
        'birth',
        'document_id',
        'document_number',
        'nationality_id',
        'gender_id',        
        'email',
        'email_verified_at',
        'password',
        'token',
        'last_login_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
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

    // Relacionamentos

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function social_name(): HasOne
    {
        return $this->hasOne(SocialName::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function phone(): HasMany
    {
        return $this->hasMany(Phone::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function academic(): HasOne
    {
        return $this->hasOne(Academic::class);
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

    public function parent_email(): HasOne
    {
        return $this->hasOne(ParentEmail::class);
    }   

    public function health(): HasOne
    {
        return $this->hasOne(Health::class);
    }

    public function pne(): HasOne
    {
        return $this->hasOne(Pne::class);
    }

    public function social_program(): HasOne
    {
        return $this->hasOne(SocialProgram::class);
    }

    public function archives(): HasMany
    {
        return $this->hasMany(Archive::class);
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
    }

    public function inscription(): HasOne
    {
        return $this->hasOne(Inscription::class);
    }

    public static function getWithoutInscription()
    {
        return static::where('role', 'user')
            ->doesntHave('inscription')
            ->get();
    }

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

    public function hasInscription(): bool
    {
        return $this->inscription()->exists();
    }

    // Acessors e Mutators
    public function getCpfAttribute($value)
    {
        // Formata o CPF no formato 'xxx.xxx.xxx-xx'
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
    }

    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = preg_replace('/\D/', '', $value);
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

    /**
     * Remove todos os caracteres não numéricos do atributo 'document_number'.
     *
     * @param string $value    O valor do atributo 'document_number'
     */
    public function setDocumentNumberAttribute($value)
    {
        $this->attributes['document_number'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'document_number' formatado como 99.999.999-9.
     *
     * @param string $value    O valor do atributo 'document_number'
     * @return string    O valor do atributo 'document_number' formatado como 99.999.999-9
     */
    public function getDocumentNumberAttribute($value)
    {
        // Formato ao retornar deve ser 99.999.999-9
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{1})/', '$1.$2.$3-$4', $value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->toUpper($value);
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
    
    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_users_without_inscription'));
        static::deleted(fn() => Cache::forget('global_users_without_inscription'));
    }
}