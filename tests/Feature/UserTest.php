<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * Schema test
     *
     * @return void
     */
    public function test_users_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('users', [
                'email', 'password', 'role'
            ]), 1
        );
    }
}
