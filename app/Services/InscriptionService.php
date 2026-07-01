<?php

namespace App\Services;

use App\Mail\SendMail;
use App\Models\Inscription;
use App\Models\Process;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InscriptionService
{
    public function __construct(
        private UserService $userService
    ) {}

    public function store(): void
    {
        $user = Auth::user();

        $steps = collect(range(1, 7))
            ->mapWithKeys(fn ($step) => ["step{$step}" => session()->get("step{$step}", [])]);

        $data = array_merge(...$steps->values()->toArray());

        DB::transaction(function () use ($user, $data, $steps) {

            $user->update([
                'cpf' => data_get($data, 'cpf'),
                'name' => data_get($data, 'name'),
                'birth' => data_get($data, 'birth'),
                'gender' => data_get($data, 'gender'),
                'nationality' => data_get($data, 'nationality'),
                'phone' => data_get($data, 'phone'),
                'zip' => data_get($data, 'zip'),
                'street' => data_get($data, 'street'),
                'home' => data_get($data, 'home'),
                'complement' => data_get($data, 'complement'),
                'burgh' => data_get($data, 'burgh'),
                'city' => data_get($data, 'city'),
                'state' => data_get($data, 'state'),
                'nis' => (data_get($data, 'social_program') == '1') ? data_get($data, 'nis') : '',
                // O OtherRequest valida e nomeia o campo como 'health_issue' (ver rules()/messages()).
                'health_issue' => (data_get($data, 'health') == '1') ? data_get($data, 'health_issue') : '',
                'role' => 'user',
            ]);

            $sp = Process::latest('id')->first() ?? throw new \Exception('Processo Seletivo não encontrado.');

            if (Inscription::where('user_id', $user->id)->where('process_id', $sp->id)->exists()) {
                throw new \Exception('Inscrição já realizada.');
            }

            $user->document()->updateOrCreate(
                [],
                [
                    'type' => data_get($data, 'doc_type'),
                    'number' => data_get($data, 'doc_number'),
                    'expedition' => data_get($data, 'expedition'),
                ]
            );

            if (data_get($data, 'social_name_option') === '1') {
                $user->lgbt()->updateOrCreate(
                    [],
                    [
                        'name' => data_get($data, 'social_name'),
                        'authorization' => data_get($data, 'authorization'),
                    ]
                );
            }

            $user->certificate()->updateOrCreate(
                [],
                [
                    'type' => data_get($data, 'certificateModel'),
                    'number' => data_get($data, 'new_number') ?? data_get($data, 'old_number'),
                    'fls' => data_get($data, 'fls'),
                    'book' => data_get($data, 'book'),
                    'city' => data_get($data, 'municipality'),
                ]
            );

            $user->academic()->updateOrCreate([], [
                'school' => data_get($data, 'school_name'),
                'city' => data_get($data, 'school_city'),
                'state' => data_get($data, 'school_state'),
                'year' => data_get($data, 'school_year'),
                'ra' => data_get($data, 'school_ra'),
            ]);

            $user->mother()->updateOrCreate(
                [],
                [
                    'name' => data_get($data, 'mother'),
                    'phone' => data_get($data, 'mother_phone'),
                ]
            );

            if (! empty(data_get($data, 'father'))) {
                $user->father()->updateOrCreate(
                    [],
                    [
                        'name' => data_get($data, 'father'),
                        'phone' => data_get($data, 'father_phone'),
                    ]
                );
            }

            if (data_get($data, 'respLegalOption') === '1') {
                $degree = data_get($data, 'degree');

                $user->guardian()->updateOrCreate(
                    [],
                    [
                        'name' => data_get($data, 'responsible'),
                        'phone' => data_get($data, 'responsible_phone'),
                        'degree' => $degree,
                        'kinship' => ($degree == '8' && ! empty(data_get($data, 'kinship'))) ? data_get($data, 'kinship') : '',
                    ]
                );
            }

            $user->parent_email()->updateOrCreate(
                [],
                [
                    'address' => data_get($data, 'parents_email'),
                ]
            );

            if (data_get($data, 'pne') === '1') {
                $pne = data_get($data, 'pne');

                $user->pne()->updateOrCreate(
                    [],
                    [
                        'pne' => $pne,
                        'description' => ($pne == '1') ? data_get($data, 'accessibility_description') : '',
                        'support' => ($pne == '1') ? data_get($data, 'pne_description') : '',
                        'report' => ($pne == '1') ? data_get($data, 'pne_report') : '',
                    ]
                );
            }

            $inscription = Inscription::create([
                'user_id' => $user->id,
                'course_id' => data_get($data, 'course_id'),
                'process_id' => $sp->id,
            ]);

            $pdf = Pdf::loadView('inscription.pdf.email', compact('user', 'inscription', 'sp'));

            $filename = 'comprovante_'.preg_replace('/[^0-9]/', '', (string) $user->cpf).'.pdf';
            $path = storage_path('app/public/'.$filename);
            $pdf->save($path);

            Mail::to($user->email)->send(new SendMail(
                subject: 'Confirmação de Inscrição',
                content: ['name' => $user->name],
                view: 'emails.register',
                attachment: $path
            ));

            Storage::disk('public')->delete($filename);

            $allKeys = collect(range(1, 7))
                ->flatMap(fn ($step) => ["step{$step}", "step{$step}_done"])
                ->toArray();

            session()->forget($allKeys);
        });
    }
}