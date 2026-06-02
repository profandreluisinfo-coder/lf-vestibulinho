<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\CommunicateAttachment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CommunicateAttachmentController extends Controller
{
    public function destroy(
        CommunicateAttachment $attachment
    ): RedirectResponse {

        // Remove o arquivo físico
        Storage::disk('public')->delete($attachment->path);

        // Remove o registro do banco
        $attachment->delete();

        return back()->with(
            'success',
            'Anexo removido com sucesso.'
        );
    }
}