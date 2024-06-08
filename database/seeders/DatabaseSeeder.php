<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Rafael',
            'email' => 'rafaelsantoscostat@gmail.com',
            'ra' => '10134152',
            'is_admin' => 1,
            'is_blocked' => 0
        ]);

        $this->call(AcademiaSeeder::class);

       
    }
}
