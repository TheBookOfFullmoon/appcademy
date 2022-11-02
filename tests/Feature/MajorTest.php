<?php

namespace Tests\Feature;

use App\Models\Major;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MajorTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Schema test
     *
     * @return void
     */
    public function test_majors_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('majors', [
                'name'
            ]), 1
        );
    }

    public function test_visit_majors_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('admin/majors')
            ->assertStatus(200);
    }

    public function test_visit_majors_page_unauthorized(){
        $this->get('admin/majors')
            ->assertStatus(302)
            ->assertRedirect('login');
    }

    public function test_visit_majors_page_beside_admin_role(){
        $user = User::factory()->create(['role' => 'lecturer']);

        $this->actingAs($user)->get('admin/majors')
            ->assertStatus(302)->assertRedirect('lecturers');

        $user = User::factory()->create(['role' => 'student']);

        $this->actingAs($user)->get('admin/majors')
            ->assertStatus(302)->assertRedirect('students');
    }

    public function test_visit_create_major_page(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->get('admin/majors/create')
            ->assertStatus(200);
    }


    public function test_add_new_major(){
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post('admin/majors', [
            'name' => 'new major'
        ])->assertStatus(302)->assertRedirect('admin/majors')
            ->assertSessionHas('success', 'Successfully created new major');

        $this->assertDatabaseHas('majors', [
            'name' => 'new major'
        ]);
    }

    public function test_add_new_major_invalid_input(){
        $user = User::factory()->create(['role'=>'admin']);
        $this->actingAs($user)->post('admin/majors', [
            'name' => ''
        ])->assertStatus(302)->assertSessionHasErrors('name');

        $this->assertDatabaseCount('majors', 0);
    }

    public function test_visit_edit_major_page(){
        $user = User::factory()->create(['role'=>'admin']);
        $major = Major::factory()->create();

        $this->actingAs($user)->get("admin/majors/{$major->id}/edit")
            ->assertStatus(200);
    }

    public function test_update_major(){
        $user = User::factory()->create(['role'=>'admin']);
        $major = Major::factory()->create();

        $this->actingAs($user)->put("admin/majors/{$major->id}", [
            'name' => 'update major'])
            ->assertStatus(302)->assertRedirect('admin/majors')
            ->assertSessionHas('success', 'Successfully updated major');
    }

    public function test_update_major_invalid_input(){
        $user = User::factory()->create(['role'=>'admin']);
        $major = Major::factory()->create();

        $this->actingAs($user)->put("admin/majors/{$major->id}", [
            'name' => ''])
            ->assertStatus(302)
            ->assertSessionHasErrors('name');
    }

    public function test_destroy_major(){
        $user = User::factory()->create(['role'=>'admin']);
        $major = Major::factory()->create();

        $this->assertDatabaseCount('majors', 1);

        $this->actingAs($user)->delete("admin/majors/{$major->id}")
            ->assertStatus(302)->assertRedirect('admin/majors')
            ->assertSessionHas('success', 'Successfully deleted major');

        $this->assertDatabaseCount('majors', 0);
    }
}
