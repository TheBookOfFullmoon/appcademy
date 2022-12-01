<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Lecturer;
use App\Models\Major;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        run this once

        if(User::count() == 0){
            \App\Models\User::factory()->create([
                'email' => 'admin@mail.com',
                'role' => 'admin'
            ]);
        }

        Major::factory(5)->create();

        User::factory(10)->create(['role' => 'student'])->each(function($user){
            $student = Student::factory()->make(['major_id' => rand(1, Major::count())]);
            $user->student()->save($student);
        });

        User::factory(10)->create(['role' => 'lecturer'])->each(function ($user){
            $lecturer = Lecturer::factory()->make();
            $user->lecturer()->save($lecturer);
        });

        Subject::factory(10)->create(['lecturer_id' => rand(1, Lecturer::count())])->each(function ($subject){
           $schedule = Schedule::factory()->make();
           $subject->schedule()->save($schedule);

           $subject->update([
               'lecturer_id' => rand(1, Lecturer::count())
           ]);

           $subject->students()->attach(rand(1, Student::count()));
        });

//        Major::factory(20)->create();
    }
}
