<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Contracts\View\View;
use Mews\Purifier\Facades\Purifier;

class FaqController extends Controller
{
    /**
     * Exibe publicamente as perguntas frequentes.
     */
    public function index(): View
    {
        $faqs = Faq::with('category')
            ->where('status', true)
            ->orderBy('order')
            ->get()
            ->map(fn($faq) => [
                'id' => $faq->id,
                'cat' => $faq->category?->normalized_name,
                'q' => $faq->question,
                'a' => Purifier::clean($faq->answer ?? '', 'faq'),
            ])
            ->values();

        return view('site.faqs.index', compact('faqs'));
    }
}
