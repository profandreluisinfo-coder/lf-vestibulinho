<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
        'gender',
        'nationality',
        'phone',
        'email',
        'email_verified_at',
        'zip',
        'street',
        'home',
        'complement',
        'burgh',
        'city',
        'state',
        'nis',
        'health_issue',
        'role',
        'password',
        'token',
        'last_login_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
        ];
    }

    // Relacionamentos
    public function lgbt(): HasOne
    {
        return $this->hasOne(Lgbt::class);
    }

    public function document(): HasOne
    {
        return $this->hasOne(Document::class);
    }

    public function certificate(): HasOne
    {
        return $this->hasOne(Certificate::class);
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
        return static::where('role', 'guest')->doesntHave('inscription')->get();
    }

    public function hasConfirmedCall(): bool
    {
        $result = $this->inscription->exam_result ?? null;

        if (! $result) {
            return false;
        }

        return Call::where('exam_result_id', $result->id)
            ->whereHas('callList', fn ($query) => $query->where('status', 'completed'))
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $this->toUpper($value);
    }

    public function getGenderAttribute($value)
    {
        $genders = [
            '1' => 'MASCULINO',
            '2' => 'FEMININO',
            '3' => 'OUTRO',
            '4' => 'PREFIRO NÃO INFORMAR',
        ];

        return $genders[$value] ?? $value;  // Se não encontrar, retorna o valor original
    }

    public function getNationalityAttribute($value)
    {
        $nationalities = [
            '1' => 'BRASILEIRA',
            '2' => 'BRASILEIRA NATURALIZADA',
            '3' => 'ESTRANGEIRA',
            '4' => 'PORTUGUESA (COM ESTATUTO DE IGUALDADE)',
        ];

        return $nationalities[$value] ?? $value;  // Se não encontrar, retorna o valor original
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'phone'.
     *
     * @param  string  $value  O valor do atributo 'phone'
     */
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = str_replace(['(', ')', ' ', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'phone' formatado como (99) 99999-9999.
     *
     * @param  string  $value  O valor do atributo 'phone'
     * @return string O valor do atributo 'phone' formatado como (99) 99999-9999
     */
    public function getPhoneAttribute($value)
    {
        // return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value);
        return $this->formatPhone($value);
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'zip' (CEP).
     *
     * @param  string  $value  O valor do atributo 'zip'
     */
    public function setZipAttribute($value)
    {
        $this->attributes['zip'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'zip' (CEP) formatado como 99.999-99.
     *
     * @param  string  $value  O valor do atributo 'zip'
     * @return string O valor do atributo 'zip' formatado como 99.999-99
     */
    public function getZipAttribute($value)
    {
        return preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value);
    }

    /**
     * Converte o valor do atributo 'city' para maiúsculo.
     *
     * @param  string  $value  O valor do atributo 'city'
     * @return $this
     */
    public function getCityAttribute($value)
    {
        return Str::of($value)->upper();
    }

    /**
     * Converte o valor do atributo 'state' para maiúsculo.
     *
     * @param  string  $value  O valor do atributo 'state'
     * @return $this
     */
    public function getStateAttribute($value)
    {
        return Str::of($value)->upper();
    }

    /**
     * Remove todos os caracteres não numéricos do atributo 'nis'.
     *
     * @param  string  $value  O valor do atributo 'nis'
     * @return void
     */
    public function setNisAttribute($value)
    {
        $this->attributes['nis'] = str_replace(['.', '-'], '', $value);
    }

    /**
     * Retorna o valor do atributo 'nis' formatado como 999.99999-9.
     *
     * @param  string  $value  O valor do atributo 'nis'
     * @return string O valor do atributo 'nis' formatado como 999.99999-9
     */
    public function getNisAttribute($value)
    {
        return preg_replace('/(\d{3})(\d{5})(\d{2})(\d{1})/', '$1.$2.$3-$4', $value);
    }

    /**
     * Formata o valor do atributo 'telefone' no formato (XX) XXXX-XXXX.
     * O formato aceita 11 dígitos seguidos de 1 letra ou número.
     * Se o valor não seguir o formato, ele será retornado sem alterações.
     *
     * @param  string  $value  O valor do atributo 'telefone'
     * @return string O valor do atributo 'telefone' formatado como (XX) XXXX-XXXX
     */
    private function formatPhone($value)
    {
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1) $2-$3', $value);
    }

    /**
     * Converte o valor para maiúsculas, tratando nulos e espaços.
     */
    private function toUpper(?string $value): ?string
    {
        return $value ? Str::of(trim($value))->upper() : null;
    }

    /**
     * Defina o valor de um determinado atributo no modelo.
     *
     * Se o valor for uma string vazia, ele será convertido em nulo.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        // Se o valor for string vazia, converte para null
        if ($value === '') {
            $value = null;
        }

        return parent::setAttribute($key, $value);
    }

    protected static function booted()
    {
        static::saved(fn () => Cache::forget('global_users_without_inscription'));
        static::deleted(fn () => Cache::forget('global_users_without_inscription'));
    }
}
