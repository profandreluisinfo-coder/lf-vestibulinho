<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'question',
        'answer',
        'user_id',
        'order',
    ];

    /**
     * Obtenha o usuário que realizou a pergunta frequentemente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getActiveFaqs()
    {
        return cache()->remember('active_faqs', 3600, function () {
            return self::where('status', true)
                       ->orderBy('order', 'asc')
                       ->get();
        });
    }
    
    public static function hasActiveFaqs()
    {
        return cache()->remember('has_active_faqs', 3600, function () {
            return self::where('status', true)->exists();
        });
    }
    
    // Limpar cache ao salvar/excluir
    protected static function booted()
    {
        static::saved(fn() => cache()->forget(['active_faqs', 'has_active_faqs']));
        static::deleted(fn() => cache()->forget(['active_faqs', 'has_active_faqs']));
    }
}