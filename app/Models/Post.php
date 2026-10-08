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

    // Detecta se a url é um vídeo (YouTube, Vimeo ou arquivo direto)
    public function getVideoAttribute(): ?array
    {
        $url = $this->url;

        if (!$url || !Str::startsWith($url, ['http://', 'https://'])) {
            return null;
        }

        $host = preg_replace('/^www\./', '', strtolower(parse_url($url, PHP_URL_HOST) ?? ''));
        $path = parse_url($url, PHP_URL_PATH) ?? '';

        // YouTube
        if (in_array($host, ['youtube.com', 'm.youtube.com', 'youtu.be'])) {
            if ($host === 'youtu.be') {
                $id = ltrim($path, '/');
            } else {
                parse_str(parse_url($url, PHP_URL_QUERY) ?? '', $query);
                $id = $query['v'] ?? '';
            }

            if (preg_match('/^[\w-]{11}$/', $id)) {
                return ['type' => 'iframe', 'src' => "https://www.youtube.com/embed/{$id}"];
            }
        }

        // Vimeo
        if ($host === 'vimeo.com' && preg_match('#^/(\d+)#', $path, $m)) {
            return ['type' => 'iframe', 'src' => "https://player.vimeo.com/video/{$m[1]}"];
        }

        // Arquivo de vídeo direto
        $extensao = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($extensao, ['mp4', 'webm', 'ogg'])) {
            return ['type' => 'file', 'src' => $url];
        }

        return null;
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
