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

    protected $hidden = ['password', 'remember_token',];

    protected function casts(): array
    {
        return [
            'birth' => 'date',
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

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function nationality(): BelongsTo
    {
        return $this->belongsTo(Nationality::class);
    }

    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
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

    // Acessors
    public function getCpfAttribute($value)
    {
        // Formata o CPF no formato 'xxx.xxx.xxx-xx'
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
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
    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = preg_replace('/\D/', '', $value);
    }

    protected static function booted()
    {
        static::saved(fn() => Cache::forget('global_users_without_inscription'));
        static::deleted(fn() => Cache::forget('global_users_without_inscription'));
    }
}