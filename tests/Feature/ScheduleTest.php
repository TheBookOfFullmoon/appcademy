<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * schema test
     *
     * @return void
     */
    public function test_schedules_has_expected_columns()
    {
        $this->assertTrue(
            Schema::hasColumns('schedules', [
                'day_name', 'room', 'subject_id'
            ]), 1
        );
    }
}
