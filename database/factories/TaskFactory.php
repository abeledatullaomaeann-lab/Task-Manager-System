<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'status' => fake()->randomElement(['pending', 'done']),
            'due_date' => fake()->dateTimeBetween('now', '+2 weeks'),
        ];
    }
}