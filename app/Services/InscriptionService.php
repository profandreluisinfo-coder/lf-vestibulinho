<?php

namespace App\Services;

use App\Mail\SendMail;
use App\Models\Inscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InscriptionService
{
    /**
     * Armazenar dados de inscrição de um determinado usuário no banco de dados.

     */
    public function store(): void
    {
        $user = Auth::user();

        $steps = collect(range(1, 7))
            ->mapWithKeys(fn($step) => ["step{$step}" => session()->get("step{$step}", [])]);

        $data = array_merge(...$steps->values()->toArray());

        DB::transaction(function () use ($user, $data, $steps) {
            $user->update([
                'cpf' => $data['cpf'],
                'name' => $data['name'],
                'social_name' => $data['social_name'],
                'birth' => $data['birth'],
                'gender' => $data['gender'],
                'pne' => ($data['accessibility'] == '1' && !empty($data['accessibility_description']))
                    ? 1 : 0,
            ]);

            if (Inscription::where('user_id', $user->id)->where('course_id', $data['course_id'])->exists()) {
                throw new \Exception('Inscrição já realizada.');
            }

            $user->user_detail()->updateOrCreate([], [
                'nationality' => $data['nationality'],
                'doc_type' => $data['doc_type'],
                'doc_number' => $data['doc_number'],
                'phone' => $data['phone'],
                'zip' => $data['zip'],
                'street' => $data['street'],
                'number' => $data['number'],
                'complement' => data_get($data, 'complement'),
                'burgh' => $data['burgh'],
                'city' => $data['city'],
                'state' => $data['state'],
                'school_name' => $data['school_name'],
                'school_city' => $data['school_city'],
                'school_state' => $data['school_state'],
                'school_year' => $data['school_year'],
                'school_ra' => $data['school_ra'],
                'new_number' => data_get($data, 'new_number'),
                'fls' => data_get($data, 'fls'),
                'book' => data_get($data, 'book'),
                'old_number' => data_get($data, 'old_number'),
                'municipality' => data_get($data, 'municipality'),
                'mother' => $data['mother'],
                'mother_phone' => data_get($data, 'mother_phone'),
                'father' => data_get($data, 'father'),
                'father_phone' => data_get($data, 'father_phone'),
                'responsible' => data_get($data, 'responsible'),
                'degree' => data_get($data, 'degree'),
                'kinship' => ($data['degree'] == '8' && !empty(data_get($data, 'kinship')))
                    ? $data['kinship'] : '',
                'responsible_phone' => data_get($data, 'responsible_phone'),
                'parents_email' => $data['parents_email'],
                'accessibility' => ($data['accessibility'] == '1' && !empty($data['accessibility_description']))
                    ? $data['accessibility_description'] : '',
                'nis' => (data_get($data, 'social_program') == '1') ? data_get($data, 'nis') : '',
                'health' => (data_get($data, 'health') == '1') ? data_get($data, 'health_issue') : '',
            ]);

            $inscription = Inscription::create([
                'user_id' => $user->id,
                'course_id' => $data['course_id'],
            ]);

            $pdf = Pdf::loadView('pdf.inscricao', compact('user', 'inscription'));
            $filename = 'Inscricao_' . preg_replace('/[^0-9]/', '', $user->cpf) . '.pdf';
            $path = storage_path('app/public/' . $filename);
            $pdf->save($path);

            Mail::to($user->email)->send(new SendMail(
                subject: 'Confirmação de Inscrição',
                content: ['name' => $user->social_name ?: $user->name],
                view: 'mail.register',
                attachment: $path
            ));

            Storage::disk('public')->delete($filename);
            
            // Limpa a sessão no final da transação
            session()->forget($steps->keys()->toArray());
        });
    }
}
