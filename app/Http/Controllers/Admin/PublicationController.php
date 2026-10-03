<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PublicationController extends Controller
{
    /**
     * Tela com as listas e o estado de cada uma (pública ou oculta).
     */
    public function index(): View
    {
        return view('admin.publications.index', [
            'lists' => Setting::PUBLICATION_LISTS,
            'released' => Setting::releasedPublications(),
        ]);
    }

    /**
     * Libera ou oculta uma lista na página pública de Publicações.
     */
    public function toggle(string $list): RedirectResponse
    {
        abort_unless(array_key_exists($list, Setting::PUBLICATION_LISTS), 404);

        $setting = Setting::first() ?? new Setting();

        $publications = $setting->publications ?? [];
        $publications[$list] = ! ($publications[$list] ?? false);

        $setting->publications = $publications;
        $setting->save();

        $label = Setting::PUBLICATION_LISTS[$list]['label'];

        return back()->with(
            'success',
            $publications[$list]
                ? "A lista \"{$label}\" foi liberada para o público."
                : "A lista \"{$label}\" foi ocultada do público."
        );
    }
}