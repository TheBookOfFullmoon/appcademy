<?php

namespace Tests\Feature;

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
}
