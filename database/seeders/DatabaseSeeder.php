<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();
	\App\Models\Company::factory()->create([
            'name' => 'PolygonLab',
            'description' => '',
            'id' => 1
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test Agent',
            'email' => 'test@test.com',
            'password' => 'secret',
            'role' => 'user',
            'company_id' => 1
        ]);
    }
}
