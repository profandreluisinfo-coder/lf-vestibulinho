<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Process;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Throwable;

class ProcessController extends Controller
{
    /**
     * Exibe o formulário do calendário (criar ou editar).
     * Como só existe um calendário no sistema, busca o primeiro ou cria um vazio.
     */
    public function edit(): View
    {
        return view('admin.process.edit');
    }

    /**
     * Salva ou atualiza os dados do Processo Seletivo e os eventos relacionados.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $newEditalPath = null;

        try {
            $validated = $request->validate(
                [
                    'year' => 'required|integer',
                    'edital' => 'required|file|mimetypes:application/pdf',
                    'start' => 'required|date',
                    'end' => 'required|date|after_or_equal:start',
                    'location_publish' => 'required|date|after_or_equal:end',
                    'exam_date' => 'required|date|after_or_equal:end',
                    'answer_publish' => 'required|date|after_or_equal:exam_date',
                    'revision_start' => 'required|date|after_or_equal:exam_date',
                    'revision_end' => 'required|date|after_or_equal:revision_start',
                    'result_publish' => 'required|date|after_or_equal:answer_publish',
                    'enrol_start' => 'required|date|after_or_equal:result_publish',
                    'enrol_remaining' => 'required|date|after_or_equal:enrol_start',
                ],
                [
                    'year.required' => 'O campo ano do vestibulinho é obrigatório.',
                    'year.integer' => 'O campo ano do vestibulinho deve ser um número inteiro.',

                    'edital.required' => 'O campo edital do calendário é obrigatório.',
                    'edital.file' => 'O campo edital do calendário deve ser um arquivo.',
                    'edital.mimetypes' => 'O campo edital do calendário deve ser um PDF.',

                    'start.required' => 'O campo período de inscrições (início) é obrigatório.',
                    'start.date' => 'O campo período de inscrições deve ser uma data.',

                    'end.required' => 'O campo período de inscrições (fim) é obrigatório.',
                    'end.date' => 'O campo período de inscrições deve ser uma data.',
                    'end.after_or_equal' => 'O campo período de inscrições (fim) deve ser posterior ou igual ao início.',

                    'location_publish.required' => 'O campo data de publicação do local do exame é obrigatório.',
                    'location_publish.date' => 'O campo data de publicação do local do exame deve ser uma data.',
                    'location_publish.after_or_equal' => 'O campo data de publicação do local do exame deve ser posterior ou igual ao fim das inscrições.',

                    'exam_date.required' => 'O campo data do exame é obrigatório.',
                    'exam_date.date' => 'O campo data do exame deve ser uma data.',
                    'exam_date.after_or_equal' => 'O campo data do exame deve ser posterior ou igual ao fim das inscrições.',

                    'answer_publish.required' => 'O campo data de publicação do gabarito é obrigatório.',
                    'answer_publish.date' => 'O campo data de publicação do gabarito deve ser uma data.',
                    'answer_publish.after_or_equal' => 'O campo data de publicação do gabarito deve ser posterior ou igual à data do exame.',

                    'revision_start.required' => 'O campo período de revisão de prova (início) é obrigatório.',
                    'revision_start.date' => 'O campo período de revisão de prova deve ser uma data.',
                    'revision_start.after_or_equal' => 'O campo período de revisão de prova deve ser posterior ou igual à data do exame.',

                    'revision_end.required' => 'O campo período de revisão de prova (fim) é obrigatório.',
                    'revision_end.date' => 'O campo período de revisão de prova deve ser uma data.',
                    'revision_end.after_or_equal' => 'O campo período de revisão de prova (fim) deve ser posterior ou igual ao início.',

                    'result_publish.required' => 'O campo data de publicação do resultado final é obrigatório.',
                    'result_publish.date' => 'O campo data de publicação do resultado final deve ser uma data.',
                    'result_publish.after_or_equal' => 'O campo data de publicação do resultado final deve ser posterior ou igual à publicação do gabarito.',

                    'enrol_start.required' => 'O campo período de matrículas (1ª chamada) é obrigatório.',
                    'enrol_start.date' => 'O campo período de matrículas (1ª chamada) deve ser uma data.',
                    'enrol_start.after_or_equal' => 'O campo período de matrículas (1ª chamada) deve ser posterior ou igual à publicação do resultado final.',

                    'enrol_remaining.required' => 'O campo período de matrículas (2ª chamada) é obrigatório.',
                    'enrol_remaining.date' => 'O campo período de matrículas (2ª chamada) deve ser uma data.',
                    'enrol_remaining.after_or_equal' => 'O campo período de matrículas (2ª chamada) deve ser posterior ou igual à 1ª chamada.',
                ]
            );

            $file = $request->file('edital');

            if (! $file || ! $file->isValid()) {
                return alertError('Envie um arquivo PDF válido.');
            }

            /*
                * Cria um nome seguro e único.
                * Exemplo:
                * edital_vestibulinho_2026_1718920000.pdf
            */
            $fileName = 'edital_vestibulinho_'
                .$validated['year']
                .'_'
                .time()
                .'.'
                .$file->getClientOriginalExtension();

            $newEditalPath = $file->storeAs('notices', $fileName, 'public');

            if (! $newEditalPath) {
                return alertError('Não foi possível salvar o edital. Tente novamente.');
            }

            $eventData = [
                'start' => $validated['start'],
                'end' => $validated['end'],
                'location_publish' => $validated['location_publish'],
                'exam_date' => $validated['exam_date'],
                'answer_publish' => $validated['answer_publish'],
                'revision_start' => $validated['revision_start'],
                'revision_end' => $validated['revision_end'],
                'result_publish' => $validated['result_publish'],
                'enrol_start' => $validated['enrol_start'],
                'enrol_remaining' => $validated['enrol_remaining'],
            ];

            DB::transaction(function () use (
                $validated,
                $eventData,
                $newEditalPath
            ) {
                $process = Process::latest('id')->first();

                /*
                * Guarda o edital anterior para apagá-lo apenas
                * depois de atualizar o registro com sucesso.
                */
                $oldEditalPath = $process?->edital;

                if ($process) {
                    $process->update([
                        'year' => $validated['year'],
                        'edital' => $newEditalPath,
                    ]);
                } else {
                    $process = Process::create([
                        'year' => $validated['year'],
                        'edital' => $newEditalPath,
                    ]);
                }

                /*
                * Atualiza o evento mais recente.
                * Se ainda não existir evento, cria um novo.
                */
                $event = $process->latestEvent;

                if ($event) {
                    $event->update($eventData);
                } else {
                    $process->events()->create($eventData);
                }

                /*
                * Remove o PDF antigo somente após a atualização
                * do processo seletivo ter funcionado.
                */
                if (
                    $oldEditalPath &&
                    $oldEditalPath !== $newEditalPath &&
                    Storage::disk('public')->exists($oldEditalPath)
                ) {
                    Storage::disk('public')->delete($oldEditalPath);
                }
            });

            return alertSuccess(
                'Calendário definido com sucesso!',
                'admin.process.show'
            );
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Existem erros no formulário. Verifique os campos destacados.');
        } catch (Throwable $e) {
            /*
            * Se o banco falhar depois de salvar o arquivo,
            * remove o novo PDF para não deixar arquivo órfão.
            */
            if (
                $newEditalPath &&
                Storage::disk('public')->exists($newEditalPath)
            ) {
                Storage::disk('public')->delete($newEditalPath);
            }

            Log::error('Erro ao salvar calendário do processo seletivo.', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Não foi possível salvar o calendário. Tente novamente.');
        }
    }

    /**
     * Exibe uma lista com as datas do vestibulinho.
     */
    public function show(): View
    {
        return view('admin.process.show');
    }

    public function activate(Process $sp)
    {
        // Alterna entre 'open' e 'closed'
        $sp->status = $sp->status === 'open' ? 'closed' : 'open';
        $sp->save();

        Cache::forget('global_process');

        $mensagem = $sp->status === 'open'
            ? 'Processo Seletivo habilitado com sucesso!'
            : 'Processo Seletivo desabilitado com sucesso!';

        return alertSuccess($mensagem);
    }
}
