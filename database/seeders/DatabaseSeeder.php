<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default admin
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Support',
            'email' => 'support@example.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'User Ariq',
            'email' => 'ariq@example.com',
            'password' => bcrypt('password'),
        ]);

        Category::factory()->count(4)->create();
        Priority::factory()->count(3)->create();
        Department::factory()->count(3)->create();

        Ticket::factory()->count(10)->create();
    }
}
