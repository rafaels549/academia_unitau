<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academias')->insert([
            "name" => "Unitau",
            'phone' => '123-456-7890',
            'capacidade' => 20,
            'max_faltas' => 20,
            'user_id' => 1, // Ensure this user_id exists in the users table
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
