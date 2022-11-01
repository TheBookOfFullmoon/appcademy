<?php

namespace Tests\Feature;

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
}
