<?php

namespace App\Services;

use App\Mail\SendMail;
use App\Models\Inscription;
use App\Models\SelectionProcess;
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
            ->mapWithKeys(fn($step) => ["step{$step}" => session()->get("step{$step}", [])]);

        $data = array_merge(...$steps->values()->toArray());

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";

        // exit();
        
        DB::transaction(function () use ($user, $data, $steps) {

            $user->update([
                'cpf' => $data['cpf'],
                'name' => $data['name'],
                'birth' => $data['birth'],
                'gender_id' => $data['gender'],
                'nationality_id' => $data['nationality'],
                'document_id' => $data['doc_type'],
            ]);

            if (Inscription::where('user_id', $user->id)->where('course_id', $data['course_id'])->exists()) {
                throw new \Exception('Inscrição já realizada.');
            }

            // social_names
            $user->social_name()->updateOrCreate([],[
                'name' => $data['social_name'], // nome social
                'authorization' => data_get($data, 'authorization'),
            ]);

            // phones
            $user->phone()->updateOrCreate([],[
                'number' => '19989092246',
                // 'number' => $data['phone'],
            ]);

            // certificates
            $user->certificate()->updateOrCreate([],[
                'type' => $data['certificateModel'],

                'number' => data_get($data, 'new_number') ?? data_get($data, 'old_number'),

                'fls' => data_get($data, 'fls'),
                'book' => data_get($data, 'book'),
                'municipality' => data_get($data, 'municipality'),
            ]);

            // addresses
            $user->address()->updateOrCreate([], [
                'zip' => '13170232',
                // 'zip' => $data['zip'],
                'street' => $data['street'],
                'number' => $data['number'],
                'complement' => data_get($data, 'complement'),
                'burgh' => $data['burgh'],
                'city' => $data['city'],
                'state' => $data['state'],
            ]);

            $user->academic()->updateOrCreate([], [
                'school' => $data['school_name'],
                'city' => $data['school_city'],
                'state' => $data['school_state'],
                'year' => $data['school_year'],
                'ra' => $data['school_ra'],
            ]);

            // mothers
            $user->mother()->updateOrCreate([], [
                'name' => $data['mother'],
                'phone' => data_get($data, 'mother_phone'),
            ]);

            // fathers
            $user->father()->updateOrCreate([], [
                'name' => $data['father'],
                'phone' => data_get($data, 'father_phone'),
            ]);

            // guardians
            $user->guardian()->updateOrCreate([], [
                'name' => data_get($data, 'responsible'),
                'phone' => data_get($data, 'responsible_phone'),
                'degree' => data_get($data, 'degree'),
                'kinship' => ($data['degree'] == '8' && !empty(data_get($data, 'kinship')))
                    ? $data['kinship'] : '',
            ]);

            $user->parent_email()->updateOrCreate([],[
                'email' => $data['parents_email'],
            ]);

            $user->health()->updateOrCreate([],[
                'problem' => (data_get($data, 'health') == '1') ? data_get($data, 'health_issue') : '',
            ]);

            $user->pne()->updateOrCreate([],[
                // 'pne' => $data['pne'],
                'description' => ($data['pne'] == '1') ? $data['pne_description'] : '',
                'support' => ($data['pne'] == '1') ? $data['accessibility_description'] : '',
                'report' => (data_get($data, 'pne') == '1') ? data_get($data, 'pne_report') : '',
            ]);

            $user->social_program()->updateOrCreate([],[
                'nis' => '97349155898',
            ]);
            // $user->social_program()->updateOrCreate([],[
            //     'nis' => (data_get($data, 'social_program') == '1') ? data_get($data, 'nis') : '',
            // ]);

            $sp = SelectionProcess::latest('id')->first() ?? throw new \Exception('Processo Seletivo não encontrado.');

            // dd($sp);

            Inscription::create([
                'user_id' => $user->id,
                'course_id' => $data['course_id'],
                'selection_process_id' => $sp->id
            ]);
            // $inscription = Inscription::create([
            //     'user_id' => $user->id,
            //     'course_id' => $data['course_id'],
            //     'selection_process_id' => $sp->id
            // ]);

            // $pdf = Pdf::loadView('pdf.inscription', compact('user', 'inscription', $sp->));
            // $pdf = Pdf::loadView('pdf.inscription', compact('user', 'inscription'));

            // $filename = 'comprovante_' . preg_replace('/[^0-9]/', '', $user->cpf) . '.pdf';
            // $path = storage_path('app/public/' . $filename);
            // $pdf->save($path);

            Mail::to($user->email)->send(new SendMail(
                subject: 'Confirmação de Inscrição',
                content: ['name' => $user->social_name ?: $user->name],
                view: 'emails.register',
                // attachment: $path
            ));

            // Storage::disk('public')->delete($filename);

            session()->forget($steps->keys()->toArray());
        });
    }
}