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
        $major = Major::factory()->create();

        $this->actingAs($user)->post(route('admin.students.store'), [
            'name' => 'new student',
            'email' => 'test@email.com',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
        ])->assertStatus(302)->assertRedirect(route('admin.students.index'))
            ->assertSessionHas('success', 'Successfully created new student');

        $student = Student::first();
        $major = Major::first();

        $this->assertInstanceOf(User::class, $student->user);
        $this->assertInstanceOf(Major::class, $student->major);

        $this->assertDatabaseHas('students', [
            'name' => 'new student',
            'birthday' => '02/02/2022',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $student->user->id,
            'email' => 'test@email.com',
            'role' => 'student',
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
            'email' => 'update@email.com',
            'password' => 'newpassword',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'role' => 'student',
            'major_id' => $major->id,
        ])->assertStatus(302)->assertRedirect("admin/students")
        ->assertSessionHas('success', "Successfully updated student");

        $student = Student::first();
        $major = Major::first();

        $this->assertInstanceOf(User::class, $student->user);
        $this->assertInstanceOf(Major::class, $student->major);

        $this->assertDatabaseHas('students', [
            'name' => 'update student',
            'birthday' => '02/02/2022',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
        ]);


        $this->assertDatabaseHas('users', [
            'id' => $student->user->id,
            'email' => 'update@email.com',
            'role' => 'student'
        ]);
    }

    public function test_update_student_using_student_role(){
        $user = User::factory()->create(['role' => 'student']);

        $major = Major::factory()->create();

        $student = Student::factory()->create([
            'user_id' => $user->id,
            'major_id' => $major->id
        ]);

        $this->actingAs($user)->put("students/{$student->id}", [
            'name' => 'update student',
            'email' => 'update@email.com',
            'password' => 'newpassword',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'role' => 'student',
            'major_id' => $major->id,
        ])->assertStatus(302)->assertRedirect("students")
            ->assertSessionHas('success', "Successfully updated student");

        $student = Student::first();
        $major = Major::first();

        $this->assertInstanceOf(User::class, $student->user);
        $this->assertInstanceOf(Major::class, $student->major);

        $this->assertDatabaseHas('students', [
            'name' => 'update student',
            'birthday' => '02/02/2022',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'man',
            'major_id' => $major->id,
        ]);


        $this->assertDatabaseHas('users', [
            'id' => $student->user->id,
            'email' => 'update@email.com',
            'role' => 'student'
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
        $this->assertDatabaseCount('users', 2);

        $this->actingAs($user)->delete("admin/students/{$student->id}")
            ->assertStatus(302)->assertRedirect("admin/students")
            ->assertSessionHas('success', "Successfully deleted student");


        $this->assertDatabaseCount('students', 0);
        $this->assertDatabaseCount('users', 1);
    }

    public function test_search_student(){
        $user = User::factory()->create(['role' => 'admin']);

        $studentUser = User::factory()->create(['role' => 'student']);
        $studentUser2 = User::factory()->create(['role' => 'student']);
        $major = Major::factory()->create();
        $major2 = Major::factory()->create();
        Student::factory()->create([
            'major_id' => $major->id,
            'user_id' => $studentUser->id,
            'gender' => 'Female',
            'birthday' => '2005/01/24',
        ]);
        Student::factory()->create([
            'major_id' => $major2->id,
            'user_id' => $studentUser2->id,
            'gender' => 'Male',
            'birthday' => '2004/01/24',
        ]);

        $this->assertDatabaseCount('students', 2);

        $student1 = Student::first();
        $student2 = Student::find(2);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->name
        ]))->assertStatus(200)->assertSeeText($student1->name)
            ->assertDontSeeText($student2->name);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->birth_place
        ]))->assertStatus(200)->assertSeeText($student1->birth_place)
            ->assertDontSeeText($student2->birth_place);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => '2005'
        ]))->assertStatus(200)->assertSeeText('2005')
            ->assertDontSeeText('2004');

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->address
        ]))->assertStatus(200)->assertSeeText($student1->address)
            ->assertDontSeeText($student2->address);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->gender
        ]))->assertStatus(200)->assertSeeText($student1->gender)
            ->assertDontSeeText($student2->gender);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->user->email
        ]))->assertStatus(200)->assertSeeText($student1->user->email)
            ->assertDontSeeText($student2->user->email);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->phone
        ]))->assertStatus(200)->assertSeeText($student1->phone)
            ->assertDontSeeText($student2->phone);

        $this->actingAs($user)->get(route('admin.students.search',[
            'keyword' => $student1->major->name
        ]))->assertStatus(200)->assertSeeText($student1->major->name)
            ->assertDontSeeText($student2->major->name);
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
                    'major_id' => ''],
                ['name', 'birthday', 'birth_place',
                    'address', 'gender', 'phone',
                    'major_id']
            ],
        ];
    }
}
