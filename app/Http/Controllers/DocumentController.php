<?php

namespace App\Http\Controllers;

use App\Models\Inscription;
use App\Support\DocumentServer;

class DocumentController extends Controller
{
    public function show(Inscription $inscription, string $tipo)
    {
        abort_unless(
            $inscription->user_id === auth()->id() || auth()->user()->is_admin,
            403
        );

        $path = match ($tipo) {
            'autorizacao' => $inscription->user->lgbt->authorization,
            'laudo'       => $inscription->user->pne->report,
            default       => abort(404),
        };

        return DocumentServer::respond($path);
    }
}
