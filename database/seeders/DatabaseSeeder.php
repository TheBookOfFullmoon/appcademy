<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Major;
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

//         \App\Models\User::factory()->create([
//             'email' => 'admin@example.com',
//             'role' => 'admin'
//         ]);

        Major::factory(20)->create();
    }
}
