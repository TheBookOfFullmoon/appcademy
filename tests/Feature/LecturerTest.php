<?php

namespace Tests\Feature;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LecturerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * schema test
     *
     * @return void
     */
    public function test_lecturers_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('lecturers', [
               'name', 'birthday', 'birth_place',
               'address', 'gender', 'phone', 'user_id'
            ]), 1
        );
    }

    public function test_visit_lecturers_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('admin/lecturers')
            ->assertStatus(200);

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('lecturers')
            ->assertStatus(200);
    }

    public function test_fail_visit_lecturers_page_for_unauthorized_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('admin/lecturers')
            ->assertStatus(302)->assertRedirect('students');

        $this->actingAs($user)->get('lecturers')
            ->assertStatus(302)->assertRedirect('students');
    }

    public function test_visit_create_lecturer_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('admin/lecturers/create')
            ->assertStatus(200);
    }

    public function test_fail_visit_create_lecturer_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('admin/lecturers/create')
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('admin/lecturers/create')
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_add_new_lecturer(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post('admin/lecturers', [
            'name' => 'new lecturer',
            'email' => 'lecturer@email.com',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'male',
            'role' => 'lecturer',
        ])->assertStatus(302)->assertRedirect('admin/lecturers')
            ->assertSessionHas('success', "Successfully created new lecturer");

        $lecturer = Lecturer::first();

        $this->assertInstanceOf(User::class, $lecturer->user);

        $this->assertDatabaseHas('lecturers', [
            'name' => 'new lecturer',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'male',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $lecturer->user->id,
            'email' => 'lecturer@email.com',
            'role' => 'lecturer',
        ]);
    }

    public function test_visit_edit_lecturer_page(){
        $user = User::factory()->create(['role' => 'lecturer']);
        $lecturer = Lecturer::factory()->create([
            'user_id' => $user->id
        ]);

        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get("admin/lecturers/{$lecturer->id}/edit")
            ->assertStatus(200);
    }

    public function test_fail_visit_edit_lecturer_page(){
        $user = User::factory()->create(['role' => 'student']);

        $lecturer = Lecturer::factory()->create([
           'user_id' => $user->id
        ]);

        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get("admin/lecturers/{$lecturer->id}/edit")
            ->assertStatus(302)->assertRedirect('students');

        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get("admin/lecturers/{$lecturer->id}/edit")
            ->assertStatus(302)->assertRedirect('lecturers');
    }

    public function test_update_student(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
           'user_id' => $userLecturer->id
        ]);

        $this->actingAs($user)->put("admin/lecturers/{$lecturer->id}", [
            'name' => 'update lecturer',
            'email' => 'update@email.com',
            'password' => 'newpassword',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'male',
            'role' => 'lecturer',
        ])->assertStatus(302)->assertRedirect("admin/lecturers")
            ->assertSessionHas('success', "Successfully updated lecturer");

        $lecturer = Lecturer::first();

        $this->assertInstanceOf(User::class, $lecturer->user);

        $this->assertDatabaseHas('lecturers', [
            'name' => 'update lecturer',
            'birthday' => '2022-02-02',
            'birth_place' => 'earth',
            'address' => 'earth',
            'phone' => '1234',
            'gender' => 'male',
        ]);


        $this->assertDatabaseHas('users', [
            'id' => $lecturer->user->id,
            'email' => 'update@email.com',
            'role' => 'lecturer'
        ]);
    }

    public function test_destroy_lecturer(){
        $user = User::factory()->create(['role' => 'admin']);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
           'user_id' => $user->id
        ]);

        $this->assertDatabaseCount('lecturers', 1);
        $this->assertDatabaseCount('users', 2);

        $this->actingAs($user)->delete("admin/lecturers/{$lecturer->id}")
            ->assertStatus(302)->assertRedirect("admin/lecturers")
            ->assertSessionHas('success', "Successfully deleted lecturer");


        $this->assertDatabaseCount('lecturers', 0);
        $this->assertDatabaseCount('users', 1);
    }

    /**
     * @dataProvider invalidLecturers
     *
     * @return void
     */
    public function test_invalid_lecturers_data_input($invalidData, $invalidField){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post('admin/lecturers', $invalidData)
            ->assertSessionHasErrors($invalidField)
            ->assertStatus(302);

        $userLecturer = User::factory()->create(['role' => 'lecturer']);

        $lecturer = Lecturer::factory()->create([
            'user_id' => $userLecturer->id,
        ]);

        $this->actingAs($user)->put("admin/lecturers/{$lecturer->id}", $invalidData)
            ->assertSessionHasErrors($invalidField)
            ->assertStatus(302);
    }

    public function invalidLecturers(){
        return [
            [
                ['name' => '', 'birthday' => '', 'birth_place' => '',
                    'address' => '', 'gender' => '', 'phone' => ''],
                ['name', 'birthday', 'birth_place',
                    'address', 'gender', 'phone']

            ],
            [
                ['name' => 1, 'birthday' => 'asd', 'birth_place' => 1,
                    'address' => 1, 'gender' => 1, 'phone' => 1],
                ['name', 'birthday', 'birth_place',
                    'address', 'gender', 'phone']
            ],
        ];
    }
}
