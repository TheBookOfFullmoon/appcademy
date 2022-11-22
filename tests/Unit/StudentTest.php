<?php

namespace Tests\Unit;

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_format_date_of_birth()
    {
        $student = new Student(['birthday' => '2022-12-13']);

        $this->assertEquals('13-12-2022', $student->birthday);

        $student = new Student();
        $student->birthday = '2022-01-02';

        $attributes = $student->getAttributes();

        $this->assertEquals('02/01/2022', $attributes['birthday']);
    }

//    public function test_name_is_uppercase()
//    {
//        $student = new Student(['name' => 'michael']);
//
//        $this->assertEquals('MICHAEL', $student->name);
//    }
}
