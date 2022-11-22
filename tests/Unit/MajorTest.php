<?php

namespace Tests\Unit;

use App\Models\Major;
use PHPUnit\Framework\TestCase;

class MajorTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_name_is_saved_lowecase_and_retrieved_uppercase(){
        $major = new Major();
        $major->name = 'Engineering';

        $attributes = $major->getAttributes();

        $this->assertEquals('engineering', $attributes['name']);

        $major = new Major(['name' => 'engineering']);

        $this->assertEquals('ENGINEERING', $major->name);
    }
}
