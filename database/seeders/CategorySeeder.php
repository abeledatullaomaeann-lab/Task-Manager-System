<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Work', 'color' => '#6b6bd6'],
            ['name' => 'School', 'color' => '#5b9a6b'],
            ['name' => 'Personal', 'color' => '#d98e4a'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
