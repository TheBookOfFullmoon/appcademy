<?php

namespace Tests\Feature;

use App\Models\Lecturer;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * schema test
     *
     * @return void
     */
    public function test_schedules_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('schedules', [
                'day_name', 'room', 'subject_id'
            ]), 1
        );
    }

    public function test_visit_schedules_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get("admin/schedules")
            ->assertStatus(200);
    }

    public function test_fail_visit_schedules_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get("admin/schedules")
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get("admin/schedules")
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_visit_create_schedule_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get("admin/schedules/create")
            ->assertStatus(200);
    }

    public function test_fail_visit_create_schedule_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get("admin/schedules/create")
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get("admin/schedules/create")
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_add_new_schedule_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $this->actingAs($user)->post("admin/schedules", [
            'day_name' => 'monday',
            'room' => 'A1',
            'subject_id' => $subject->id
        ])->assertStatus(302)->assertRedirect("admin/schedules")
            ->assertSessionHas('success', "Successfully created a new schedule");

        $schedule = Schedule::first();

        $this->assertInstanceOf(Subject::class, $schedule->subject);

        $this->assertDatabaseHas('schedules', [
            'day_name' => 'monday',
            'room' => 'A1',
            'subject_id' => $subject->id
        ]);
    }

    public function test_visit_edit_schedule_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $schedule = Schedule::factory()->create([
           'subject_id' => $subject->id
        ]);

        $this->actingAs($user)->get("admin/schedules/{$schedule->id}/edit")
            ->assertStatus(200);
    }

    public function test_fail_visit_edit_schedule_page_beside_admin_role(){
        $userLecturer = User::factory()->create(['role' => 'lecturer']);
        $userStudent = User::factory()->create(['role' => 'student']);


        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $schedule = Schedule::factory()->create([
            'subject_id' => $subject->id
        ]);

        $this->actingAs($userStudent)->get("admin/schedules/{$schedule->id}/edit")
            ->assertStatus(302)->assertRedirect('students');

        $this->actingAs($userLecturer)->get("admin/schedules/{$schedule->id}/edit")
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_update_schedule(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $schedule = Schedule::factory()->create([
            'subject_id' => $subject->id
        ]);

        $this->actingAs($user)->put("admin/schedules/{$schedule->id}", [
            'day_name' => 'monday',
            'room' => 'A1',
            'subject_id' => $subject->id
        ])->assertStatus(302)->assertRedirect("admin/schedules")
            ->assertSessionHas('success', "Successfully updated schedule");

        $schedule = Schedule::first();

        $this->assertInstanceOf(Subject::class, $schedule->subject);

        $this->assertDatabaseHas('schedules', [
            'day_name' => 'monday',
            'room' => 'A1',
            'subject_id' => $subject->id
        ]);
    }

    public function test_destroy_schedule(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $schedule = Schedule::factory()->create([
            'subject_id' => $subject->id
        ]);

        $this->assertDatabaseCount('schedules', 1);

        $this->actingAs($user)->delete("admin/schedules/{$schedule->id}")
            ->assertStatus(302)->assertRedirect("admin/schedules")
            ->assertSessionHas('success', "Successfully deleted schedule");

        $this->assertDatabaseCount('schedules', 0);
    }

    public function test_invalid_schedule_data_input(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $this->actingAs($user)->post("admin/schedules", [
            'day_name' => '',
            'room' => '',
            'subject_id' => $subject->id
        ])->assertStatus(302);

        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $schedule = Schedule::factory()->create([
            'subject_id' => $subject->id
        ]);

        $this->actingAs($user)->put("admin/schedules/{$schedule->id}", [
            'day_name' => '',
            'room' => '',
            'subject_id' => $subject->id
        ])->assertStatus(302);
    }
}
