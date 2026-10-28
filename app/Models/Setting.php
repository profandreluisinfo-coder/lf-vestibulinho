<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * Listas da página pública de Publicações que o administrador pode liberar.
     * A chave é o nome da rota pública sem o prefixo 'site.publications.'.
     * Para criar uma lista nova, inclua uma linha aqui.
     */
    public const PUBLICATION_LISTS = [
        'inscriptions.approved' => ['group' => 'Inscrições', 'icon' => 'person-lines-fill', 'label' => 'Inscrições deferidas'],
        'inscriptions.rejected' => ['group' => 'Inscrições', 'icon' => 'person-lines-fill', 'label' => 'Inscrições indeferidas'],
        'social-names.approved' => ['group' => 'Nome social', 'icon' => 'person-vcard', 'label' => 'Nome social deferidos'],
        'social-names.rejected' => ['group' => 'Nome social', 'icon' => 'person-vcard', 'label' => 'Nome social indeferidos'],
        'medical-reports.approved' => ['group' => 'Laudos e relatórios médicos', 'icon' => 'file-earmark-medical', 'label' => 'Laudos e relatórios deferidos'],
        'medical-reports.rejected' => ['group' => 'Laudos e relatórios médicos', 'icon' => 'file-earmark-medical', 'label' => 'Laudos e relatórios indeferidos'],
        // Resultado dos recursos (model Appeal)
        'appeals.social-names.approved' => ['group' => 'Recursos de nome social', 'icon' => 'arrow-repeat', 'label' => 'Recursos de nome social deferidos'],
        'appeals.social-names.rejected' => ['group' => 'Recursos de nome social', 'icon' => 'arrow-repeat', 'label' => 'Recursos de nome social indeferidos'],
        'appeals.medical-reports.approved' => ['group' => 'Recursos de laudos e relatórios médicos', 'icon' => 'arrow-repeat', 'label' => 'Recursos de laudos e relatórios deferidos'],
        'appeals.medical-reports.rejected' => ['group' => 'Recursos de laudos e relatórios médicos', 'icon' => 'arrow-repeat', 'label' => 'Recursos de laudos e relatórios indeferidos'],
    ];

    protected $fillable = [
        'publications',
    ];

    protected $casts = [
        'publications' => 'array', // ['inscriptions.approved' => true, ...]
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

    /**
     * Chaves das listas públicas liberadas pelo administrador.
     * Lista sem registro é lista oculta: o esquecimento nunca expõe nada.
     *
     * @return string[]
     */
    public static function releasedPublications(): array
    {
        $publications = self::first()?->publications ?? [];

        return array_keys(array_filter($publications));
    }

    // Retorna se uma lista (ex.: 'inscriptions.approved') está liberada
    public static function isPublished(string $list): bool
    {
        return in_array($list, self::releasedPublications(), true);
    }

    // Limpa o cache automaticamente quando salvar ou excluir
    protected static function booted(): void
    {
        $forget = function () {
            Cache::forget('global_settings');
            Cache::forget('global_total_publicacoes');
        };

        static::saved($forget);
        static::deleted($forget);
    }
}
