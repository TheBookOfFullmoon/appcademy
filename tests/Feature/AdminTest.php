<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdminTest extends TestCase{
    use RefreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_visit_admin_index_page()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertStatus(302)->assertRedirect(route('admin.index'))
        ->assertSessionHas('success', "You have successfully logged in.");
    }
}
