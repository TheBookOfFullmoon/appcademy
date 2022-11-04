<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * schema test
     *
     * @return void
     */
    public function test_students_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('students', [
                'name', 'birthday', 'birth_place',
                'address', 'address', 'gender',
                'phone', 'major_id', 'user_id'
            ]), 1
        );
    }

    // Admin side test
    public function test_visit_students_page(){
        $user = User::factory()->create(['role'=>'admin']);

        $this->actingAs($user)->get('admin/students')
            ->assertStatus(200);

        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('students')
            ->assertStatus(200);
    }

    public function test_fail_visit_students_page_for_unauthorized_role(){
        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('admin/students')
            ->assertStatus(302)->assertRedirect('lecturers');

        $this->actingAs($user)->get('students')
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_visit_create_student_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('admin/students/create')
            ->assertStatus(200);
    }

    public function test_fail_visit_create_student_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('admin/students/create')
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('admin/students/create')
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_add_new_student(){
        $user = User::factory()->create(['role' => 'admin']);

        $userStudent = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();

        $this->actingAs($user)->post('admin/students', [
            'name' => 'new student',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
            'user_id' => $userStudent->id,
        ])->assertStatus(302)->assertRedirect('admin/students')
            ->assertSessionHas('success', 'Successfully created new student');

        $student = Student::first();

        $this->assertInstanceOf(User::class, $student->user);

        $this->assertDatabaseHas('students', [
            'name' => 'new student',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
            'user_id' => $userStudent->id,
        ]);
    }

    public function test_visit_edit_student_page(){
        $user = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();
        $student = Student::factory()->create([
            'user_id' => $user->id,
            'major_id' => $major->id]);

        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get("admin/students/{$student->id}/edit")
            ->assertStatus(200);
    }

    public function test_fail_visit_edit_student_page(){
        $user = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();

        $student = Student::factory()->create([
            'user_id' => $user->id,
            'major_id' => $major->id
        ]);

        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get("admin/students/{$student->id}/edit")
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get("admin/students/{$student->id}/edit")
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_update_student(){
        $user = User::factory()->create(['role' => 'admin']);

        $userStudent = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();

        $student = Student::factory()->create([
          'user_id' => $userStudent->id,
          'major_id' => $major->id
        ]);

        $this->actingAs($user)->put("admin/students/{$student->id}",[
            'name' => 'update student',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
        ])->assertStatus(302)->assertRedirect("admin/students")
        ->assertSessionHas('success', "Successfully updated student");

        $student = Student::first();

        $this->assertInstanceOf(User::class, $student->user);

        $this->assertDatabaseHas('students', [
            'name' => 'update student',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
        ]);
    }

    public function test_destroy_student(){
        $user = User::factory()->create(['role' => 'admin']);

        $userStudent = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();

        $student = Student::factory()->create([
            'user_id' => $userStudent->id,
            'major_id' => $major->id
        ]);

        $this->assertDatabaseCount('students', 1);

        $this->actingAs($user)->delete("admin/students/{$student->id}")
            ->assertStatus(302)->assertRedirect("admin/students")
            ->assertSessionHas('success', "Successfully deleted student");

        $this->assertDatabaseCount('students', 0);
    }

    /**
     * @dataProvider invalidStudents
     *
     * @return void
     */
    public function test_invalid_student_data_input($invalidData, $invalidField){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post('admin/students', $invalidData)
            ->assertSessionHasErrors($invalidField)
            ->assertStatus(302);

        $userStudent = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();

        $student = Student::factory()->create([
            'user_id' => $userStudent->id,
            'major_id' => $major->id
        ]);

        $this->actingAs($user)->put("admin/students/{$student->id}", $invalidData)
            ->assertSessionHasErrors($invalidField)
            ->assertStatus(302);
    }

    public function invalidStudents(){
        return [
            [
                ['name' => '', 'birthday' => '', 'birth_place' => '',
                  'address' => '', 'gender' => '', 'phone' => '',
                  'major_id' => ''],
                ['name', 'birthday', 'birth_place',
                    'address', 'gender', 'phone',
                    'major_id']

            ],
            [
                ['name' => 1, 'birthday' => 'asd', 'birth_place' => 1,
                    'address' => 1, 'gender' => 1, 'phone' => 1,
                    'major_id' => 'ads'],
                ['name', 'birthday', 'birth_place',
                    'address', 'gender', 'phone',
                    'major_id']
            ],
        ];
    }
}
