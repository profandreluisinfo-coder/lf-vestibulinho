<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    protected $fillable = [
        'year',
        'file',
        'status',
        'user_id',
    ];

    protected $casts = [
        'status' => 'boolean',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }

    /**
     * Retorna a lista de arquivos de prova ativos.
     *
     * Este método retorna uma lista de arquivos de prova cujo status seja true.
     * A lista é ordenada por ano em ordem decrescente.
     *
     * O resultado é armazenado em cache por 1 hora.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActiveArchives()
    {
        return Cache::remember('archives_active_list', 3600, function () {
            return self::where('status', true)->orderBy('year', 'desc')->get();
        });
    }

    /**
     * Verifica se há arquivos de prova ativos.
     *
     * Este método verifica se há arquivos de prova cujo status seja true.
     * O resultado é armazenado em cache por 1 hora.
     *
     * @return bool
     */
    public static function hasActiveArchives()
    {
        return Cache::remember('archives_has_active', 3600, function () {
            return self::where('status', true)->exists();
        });
    }

    // Limpar cache ao salvar/excluir - CORRIGIDO
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('archives_active_list');
            Cache::forget('archives_has_active');
        });
        
        static::deleted(function () {
            Cache::forget('archives_active_list');
            Cache::forget('archives_has_active');
        });
    }
}