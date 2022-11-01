<?php

namespace Tests\Feature;

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
}
