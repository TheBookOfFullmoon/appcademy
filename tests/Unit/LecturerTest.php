<?php

namespace Tests\Unit;

use App\Models\Lecturer;
use PHPUnit\Framework\TestCase;

class LecturerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_format_date_of_birth()
    {
        $lecturer = new Lecturer(['birthday' => '2022-12-13']);

        $this->assertEquals('13-12-2022', $lecturer->birthday);

        $lecturer = new Lecturer();
        $lecturer->birthday = '2022-01-02';

        $attributes = $lecturer->getAttributes();

        $this->assertEquals('02/01/2022', $attributes['birthday']);
    }
}
