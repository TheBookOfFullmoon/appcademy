<?php

namespace Tests\Feature;

use App\Models\Lecturer;
use App\Models\Major;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Database\Factories\StudentSubjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentSubjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Schema test
     *
     * @return void
     */
    public function test_student_subject_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('student_subject', [
                'student_id', 'subject_id', 'grade'
            ]), 1
        );
    }

    public function test_visit_assigned_and_unassigned_student_page()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $major = Major::factory()->create();

        $userStudent = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create([
           'user_id' => $userStudent->id,
           'major_id' => $major->id
        ]);

        $userStudent2 = User::factory()->create(['role' => 'student']);
        $student2 = Student::factory()->create([
            'user_id' => $userStudent2->id,
            'major_id' => $major->id
        ]);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);
        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id,
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);
        $subject2 = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $subject->students()->attach($student->id);
        $subject2->students()->attach($student2->id);

        $this->assertDatabaseCount('student_subject', 2);

        $this->actingAs($user)->get(route('admin.subjects.assigned', $subject->id))
            ->assertStatus(200)
            ->assertSeeText($student->name)
            ->assertDontSeeText($student2->name);

        $this->actingAs($user)->get(route('admin.subjects.unassigned', $subject->id))
            ->assertStatus(200)
            ->assertSeeText($student2->name)
            ->assertDontSeeText($student->name);
    }

    public function test_assign_student(){
        $user = User::factory()->create(['role' => 'admin']);

        $major = Major::factory()->create();

        $userStudent = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create([
            'user_id' => $userStudent->id,
            'major_id' => $major->id
        ]);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);
        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id,
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $this->actingAs($user)->get(route('admin.subjects.unassigned', $subject->id))
            ->assertStatus(200)
            ->assertSeeText($student->name);

        $this->actingAs($user)->post(route('admin.subjects.assign', [$subject->id, $student->id]))
            ->assertStatus(302)
            ->assertRedirect(route('admin.subjects.unassigned', $subject->id))
            ->assertSessionHas('success', "Successfully assigned student to subject.");

        $this->actingAs($user)->get(route('admin.subjects.assigned', $subject->id))
            ->assertStatus(200)
            ->assertSeeText($student->name);

        $this->assertDatabaseCount('student_subject', 1);
    }

    public function test_unassign_student()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $major = Major::factory()->create();

        $userStudent = User::factory()->create(['role' => 'student']);
        $student = Student::factory()->create([
            'user_id' => $userStudent->id,
            'major_id' => $major->id
        ]);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);
        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id,
        ]);

        $subject = Subject::factory()->create([
            'lecturer_id' => $lecturer->id
        ]);

        $subject->students()->attach($student->id);

        $this->actingAs($user)->get(route('admin.subjects.assigned', $subject->id))
            ->assertStatus(200)
            ->assertSeeText($student->name);

        $this->actingAs($user)->delete(route('admin.subjects.unassign', [$subject->id, $student->id]))
            ->assertStatus(302)
            ->assertRedirect(route('admin.subjects.assigned', $subject->id))
            ->assertSessionHas('success', "Successfully unassigned student from subject");

        $this->actingAs($user)->get(route('admin.subjects.unassigned', $subject->id))
            ->assertStatus(200)
            ->assertSeeText($student->name);

        $this->assertDatabaseCount('student_subject', 0);
    }
}
