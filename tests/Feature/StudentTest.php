<?php

namespace Tests\Feature;

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
}
