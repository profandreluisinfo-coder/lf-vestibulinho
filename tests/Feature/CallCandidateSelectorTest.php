<?php

namespace Tests\Feature;

use App\Models\Call;
use App\Models\CallList;
use App\Models\Course;
use App\Helpers\GlobalDataHelper;
use App\Models\Inscription;
use App\Models\Pne;
use App\Models\Process;
use App\Models\Result;
use App\Models\User;
use App\Services\CallCandidateSelector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CallCandidateSelectorTest extends TestCase
{
    use RefreshDatabase;

    public function test_call_selection_reserves_the_requested_number_of_pcds_and_keeps_ties_together(): void
    {
        [$process, $course] = $this->processAndCourse();
        $first = $this->makeResult($process, $course, 1);
        $second = $this->makeResult($process, $course, 2);
        $third = $this->makeResult($process, $course, 2);
        $pcd = $this->makeResult($process, $course, 4, true);

        $selected = app(CallCandidateSelector::class)->select($process, 3, 1);

        $this->assertSame([$first->id, $second->id, $third->id, $pcd->id], $selected->modelKeys());
    }

    public function test_call_selection_does_not_select_a_result_that_was_already_called(): void
    {
        [$process, $course] = $this->processAndCourse();
        $first = $this->makeResult($process, $course, 1);
        $next = $this->makeResult($process, $course, 2);
        $callList = $this->callList($process);

        Call::create([
            'result_id' => $first->id,
            'call_list_id' => $callList->id,
            'call_number' => 1,
            'amount' => 1,
            'date' => '2027-01-13',
            'time' => '09:00',
        ]);

        $selected = app(CallCandidateSelector::class)->select($process, 1, 0);

        $this->assertSame([$next->id], $selected->modelKeys());
    }

    public function test_call_numbers_can_repeat_and_dates_do_not_block_call_creation(): void
    {
        [$process, $course] = $this->processAndCourse();
        $this->makeResult($process, $course, 1);
        $this->makeResult($process, $course, 2);
        $admin = $this->admin();

        foreach (['2027-01-12', '2027-03-01'] as $date) {
            $this->actingAs($admin)
                ->post(route('admin.calls.store'), [
                    'number' => 2,
                    'limit' => 1,
                    'pcd_limit' => 0,
                    'date' => $date,
                    'time' => '09:00',
                ])
                ->assertRedirect(route('admin.calls.index'))
                ->assertSessionHasNoErrors();
        }

        $this->assertDatabaseCount('call_lists', 2);
        $dates = CallList::query()->orderBy('id')->get()->map(fn (CallList $callList) => $callList->date->toDateString())->all();
        $this->assertSame(['2027-01-12', '2027-03-01'], $dates);
    }

    public function test_published_call_can_be_deleted(): void
    {
        [$process] = $this->processAndCourse();
        $callList = $this->callList($process);

        $this->actingAs($this->admin())
            ->from(route('admin.calls.index'))
            ->delete(route('admin.calls.destroy', $callList))
            ->assertRedirect(route('admin.calls.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('call_lists', ['id' => $callList->id]);
    }

    public function test_public_call_page_shows_only_classified_candidates_and_the_three_public_fields(): void
    {
        [$process, $course] = $this->processAndCourse();
        $classified = $this->makeResult($process, $course, 1);
        $classified->inscription->user->update(['name' => 'Nome Privado 739184']);
        $unclassified = $this->makeResult($process, $course, 2);
        $unclassified->update(['classified' => false, 'score' => 987654]);

        $callList = $this->callList($process);
        foreach ([$classified, $unclassified] as $result) {
            Call::create([
                'result_id' => $result->id,
                'call_list_id' => $callList->id,
                'call_number' => 1,
                'amount' => 2,
                'date' => '2027-01-13',
                'time' => '09:00',
            ]);
        }

        GlobalDataHelper::share();

        $this->get(route('site.calls.index'))
            ->assertOk()
            ->assertSee((string) $classified->inscription_id)
            ->assertSee((string) $classified->score)
            ->assertSee('1º')
            ->assertDontSee('Nome Privado 739184')
            ->assertDontSee('987654');
    }

    public function test_admin_all_candidates_page_shows_a_message_when_there_are_no_calls(): void
    {
        $this->processAndCourse();
        GlobalDataHelper::share();

        $this->actingAs($this->admin())
            ->get(route('admin.calls.all'))
            ->assertOk()
            ->assertSee('Ainda não há candidatos convocados neste processo seletivo.');
    }

    public function test_admin_all_candidates_page_orders_by_call_number_then_date_and_time(): void
    {
        [$process, $course] = $this->processAndCourse();

        $candidateA = $this->makeResult($process, $course, 3);
        $candidateB = $this->makeResult($process, $course, 2);
        $candidateC = $this->makeResult($process, $course, 1);
        $candidateA->inscription->user->update(['name' => 'Candidato chamada 1']);
        $candidateB->inscription->user->update(['name' => 'Candidato chamada 2 primeiro horário']);
        $candidateC->inscription->user->update(['name' => 'Candidato chamada 2 segundo horário']);

        $lists = [
            CallList::create(['process_id' => $process?->id, 'number' => 2, 'date' => '2027-01-12', 'time' => '10:00', 'status' => 'pending']),
            CallList::create(['process_id' => $process?->id, 'number' => 2, 'date' => '2027-01-12', 'time' => '09:00', 'status' => 'completed']),
            CallList::create(['process_id' => $process?->id, 'number' => 1, 'date' => '2027-01-13', 'time' => '09:00', 'status' => 'completed']),
        ];

        foreach ([[$candidateA, $lists[2]], [$candidateB, $lists[1]], [$candidateC, $lists[0]]] as [$result, $list]) {
            Call::create([
                'result_id' => $result->id,
                'call_list_id' => $list->id,
                'call_number' => $list->number,
                'amount' => 1,
                'date' => $list->date,
                'time' => $list->time,
            ]);
        }

        GlobalDataHelper::share();

        $response = $this->actingAs($this->admin())->get(route('admin.calls.all'));

        $response
            ->assertOk()
            ->assertSeeInOrder([
                'CANDIDATO CHAMADA 1',
                'CANDIDATO CHAMADA 2 PRIMEIRO HORÁRIO',
                'CANDIDATO CHAMADA 2 SEGUNDO HORÁRIO',
            ]);
    }

    private function processAndCourse(): array
    {
        $process = Process::create([
            'year' => 2027,
            'edital' => 'edital.pdf',
            'status' => 'closed',
            'exam_time' => '08:00',
        ]);

        $course = Course::create([
            'name' => 'Administração',
            'description' => 'Curso técnico',
        ]);

        $process?->courses()->attach($course->id, ['vacancies' => 10]);

        return [$process, $course];
    }

    private function makeResult(Process $process, Course $course, int $ranking, bool $pcd = false): Result
    {
        $user = User::create([
            'email' => uniqid('candidato') . '@example.test',
            'password' => Hash::make('password'),
            'role' => 'user',
            'name' => 'Candidato ' . uniqid(),
            'birth' => '2010-01-01',
        ]);

        $inscription = Inscription::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'process_id' => $process?->id,
            'status' => 'approved',
        ]);

        if ($pcd) {
            Pne::create([
                'user_id' => $user->id,
                'status' => 'accepted',
                'description' => 'Deficiência deferida',
            ]);
        }

        return Result::create([
            'inscription_id' => $inscription->id,
            'score' => 100 - $ranking,
            'ranking' => $ranking,
            'classified' => true,
        ]);
    }

    private function callList(Process $process): CallList
    {
        return CallList::create([
            'process_id' => $process?->id,
            'number' => 1,
            'date' => '2027-01-13',
            'time' => '09:00',
            'status' => 'completed',
        ]);
    }

    private function admin(): User
    {
        return User::create([
            'email' => uniqid('admin-calls') . '@example.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'name' => 'Administrador de teste',
        ]);
    }
}
