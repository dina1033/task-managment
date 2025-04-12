<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'type' => 'admin'
        ]);
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
            'password' => 'password',
            'type' => 'user'
        ]);
    }
}
