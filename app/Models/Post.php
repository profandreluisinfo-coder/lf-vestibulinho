<?php

namespace App\Models;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Post extends Model
{
    // use SoftDeletes;

    public const TYPE_NOTICIA = 'noticia';
    public const TYPE_INFO = 'comunicado';

    // $fillable - Campos que podem ser preenchidos em massa
    protected $fillable = [
        'title',
        'slug',
        'resume',
        'content',
        'image',
        'url',
        'type',
        'category_id',
        'user_id',
        'published',
        'published_at',
    ];

    // $casts - Conversão de tipos (boolean, datetime)
    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    // Relacionamento com User
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    // Scope para registros publicados
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('published', true)
            ->whereNotNull('published_at');
    }

    // Scope para notícias
    public function scopeNoticias(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_NOTICIA);
    }

    // Scope para comunicados
    public function scopeComunicados(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_INFO);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    // Mutator para slug
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title);
            }
        });
    }
}
