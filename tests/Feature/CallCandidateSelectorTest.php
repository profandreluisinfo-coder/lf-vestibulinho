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
            'enrollment_status' => 'lost',
        ]);

        $selected = app(CallCandidateSelector::class)->select($process, 1, 0);

        $this->assertSame([$next->id], $selected->modelKeys());
    }

    public function test_course_specific_reopened_vacancy_includes_candidates_waiting_for_that_course(): void
    {
        [$process, $course] = $this->processAndCourse();
        $waiting = $this->makeResult($process, $course, 1);
        $next = $this->makeResult($process, $course, 2);
        $callList = $this->callList($process);

        Call::create([
            'result_id' => $waiting->id,
            'call_list_id' => $callList->id,
            'call_number' => 1,
            'amount' => 1,
            'date' => '2027-01-13',
            'time' => '09:00',
            'course_id' => $course->id,
            'enrollment_status' => 'waiting_course',
        ]);

        $selector = app(CallCandidateSelector::class);

        $this->assertSame([$next->id], $selector->select($process, 1, 0)->modelKeys());
        $this->assertSame([$waiting->id], $selector->select($process, 1, 0, $course->id)->modelKeys());
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
                'enrollment_status' => 'awaiting',
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

        $process->courses()->attach($course->id, ['vacancies' => 10]);

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
            'process_id' => $process->id,
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
            'process_id' => $process->id,
            'number' => 1,
            'date' => '2027-01-13',
            'time' => '09:00',
            'status' => 'completed',
        ]);
    }
}
