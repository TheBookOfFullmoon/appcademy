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

class SubjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Schema test
     *
     * @return void
     */
    public function test_subjects_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('subjects', [
                'name', 'sks', 'lecturer_id'
            ]), 1
        );
    }

    public function test_visit_subjects_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);
        $lecturer = Lecturer::factory()->create(['user_id' => $userLecturer->id]);
        $subject = Subject::factory()->create(['lecturer_id' => $lecturer->id]);
        $schedule = Schedule::factory()->create(['subject_id' => $subject->id]);

        $this->actingAs($user)->get('admin/subjects')
            ->assertStatus(200)
            ->assertSeeText($subject->name)
            ->assertSeeText($lecturer->name)
            ->assertSeeText($schedule->room)
            ->assertSeeText($schedule->day_name);
    }

    public function test_fail_visit_subjects_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('admin/subjects')
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('admin/subjects')
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_visit_create_subject_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get("admin/subjects/create")
            ->assertStatus(200);
    }

    public function test_fail_visit_create_subject_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('admin/subjects/create')
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('admin/subjects/create')
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_add_new_subject(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create(['user_id' => $userLecturer->id]);

        $this->actingAs($user)->post("admin/subjects", [
            'name' => 'Intelligence System',
            'sks' => 3,
            'lecturer_id' => $lecturer->id,
            'day_name' => 'Monday',
            'room' => 'S201'
        ])->assertStatus(302)->assertRedirect("admin/subjects")
            ->assertSessionHas('success', "Successfully created a new subject");

        $this->assertDatabaseCount('schedules', 1);

        $subject = Subject::first();

        $this->assertInstanceOf(Lecturer::class, $subject->lecturer);

        $this->assertInstanceOf(Schedule::class, $subject->schedule);

        $this->assertDatabaseHas('subjects', [
            'name' => 'Intelligence System',
            'sks' => 3,
            'lecturer_id' => $lecturer->id
        ]);

        $this->assertDatabaseHas('schedules', [
            'day_name' => 'Monday',
            'room' => 'S201',
            'subject_id' => $subject->id
        ]);
    }

    public function test_visit_edit_subject_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
           'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        Schedule::factory()->create([
            'subject_id' =>  $subject->id
        ]);

        $this->actingAs($user)->get("admin/subjects/{$subject->id}/edit")
            ->assertStatus(200);
    }

    public function test_fail_visit_edit_subject_page_beside_admin_role(){
        $userStudent = User::factory()->create(['role' => 'student']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $this->actingAs($userStudent)->get("admin/subjects/{$subject->id}/edit")
            ->assertStatus(302)->assertRedirect('students');

        $this->actingAs($userLecturer)->get("admin/subjects/{$subject->id}/edit")
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_update_subject(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        Schedule::factory()->create([
            'day_name' => 'Monday',
            'subject_id' => $subject->id
        ]);

        $this->actingAs($user)->put("admin/subjects/{$subject->id}", [
            'name' => 'Updated Subject',
            'sks' => 2,
            'lecturer_id' => $lecturer->id,
            'day_name' => 'Saturday',
            'room' => 'S202'
        ])->assertStatus(302)->assertRedirect("admin/subjects")
            ->assertSessionHas('success', "Successfully updated subject");

        $subject = Subject::first();

        $this->assertInstanceOf(Lecturer::class, $subject->lecturer);
        $this->assertInstanceOf(Schedule::class, $subject->schedule);

        $this->assertDatabaseHas('subjects', [
            'name' => 'Updated Subject',
            'sks' => 2,
            'lecturer_id' => $lecturer->id
        ]);

        $this->assertDatabaseHas('schedules', [
            'day_name' => 'Saturday',
            'room' => 'S202',
            'subject_id' => $subject->id
        ]);
    }

    public function test_destroy_subject(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        Schedule::factory()->create([
            'subject_id' => $subject->id
        ]);

        $this->assertDatabaseCount('subjects', 1);
        $this->assertDatabaseCount('schedules', 1);

        $this->actingAs($user)->delete("admin/subjects/{$subject->id}")
            ->assertStatus(302)
            ->assertRedirect("admin/subjects")
            ->assertSessionHas('success', "Successfully deleted subject");

        $this->assertDatabaseCount('subjects', 0);
        $this->assertDatabaseCount('schedules', 0);
    }

    public function test_search_subject(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);
        $userLecturer2 = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);
        $lecturer2 = Lecturer::factory()->create([
            'user_id' => $userLecturer2->id
        ]);

        $subject = Subject::factory()->create([
            'sks' =>  2,
            'lecturer_id' => $lecturer->id
        ]);
        $subject2 = Subject::factory()->create([
            'sks' => 1,
            'lecturer_id' => $lecturer2->id
        ]);

        Schedule::factory()->create([
            'day_name' => 'Monday',
            'subject_id' => $subject->id
        ]);
        Schedule::factory()->create([
            'day_name' => 'Sunday',
            'subject_id' => $subject2->id
        ]);

        $this->assertDatabaseCount('lecturers', 2);
        $this->assertDatabaseCount('subjects', 2);
        $this->assertDatabaseCount('schedules', 2);

        $this->actingAs($user)->get(route('admin.subjects.search',[
            'keyword' => $subject->name
        ]))->assertStatus(200)
            ->assertSeeText($subject->name)
            ->assertDontSeeText($subject2->name);

        $this->actingAs($user)->get(route('admin.subjects.search',[
            'keyword' => $subject->sks
        ]))->assertStatus(200)
            ->assertSeeText($subject->sks)
            ->assertDontSeeText($subject2->sks);

        $this->actingAs($user)->get(route('admin.subjects.search',[
            'keyword' => $subject->schedule->room
        ]))->assertStatus(200)
            ->assertSeeText($subject->schedule->room)
            ->assertDontSeeText($subject2->schedule->room);

        $this->actingAs($user)->get(route('admin.subjects.search',[
            'keyword' => $subject->schedule->day_name
        ]))->assertStatus(200)
            ->assertSeeText($subject->schedule->day_name)
            ->assertDontSeeText($subject2->schedule->day_name);

        $this->actingAs($user)->get(route('admin.subjects.search',[
            'keyword' => $subject->lecturer->name
        ]))->assertStatus(200)
            ->assertSeeText($subject->lecturer->name)
            ->assertDontSeeText($subject2->lecturer->name);
    }

    public function test_invalid_subject_data_input(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create(['user_id' => $userLecturer->id]);

        $this->actingAs($user)->post("admin/subjects", [
            'name' => '',
            'sks' => '',
            'lecturer_id' => $lecturer->id,
            'day_name' => '',
            'room' => ''
        ])->assertStatus(302);

        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $this->actingAs($user)->put("admin/subjects/{$subject->id}", [
            'name' => '',
            'sks' => '',
            'lecturer_id' => $lecturer->id,
            'day_name' => '',
            'room' => ''
        ])->assertStatus(302);
    }
}
