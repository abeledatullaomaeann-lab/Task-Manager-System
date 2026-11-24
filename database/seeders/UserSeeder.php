<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Mae Ann',
            'email' => 'mae@example.com',
        ]);

        User::factory(3)->create();
    }
}