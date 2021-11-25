<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Type;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // question category default options
        $categories = ['Attention to Details', 'Numeric Ability and Data Analysis', 'Reasoning Ability', 'Verbal Ability'];

        foreach($categories as $category) {
            Category::firstOrCreate(['name' => $category]);
        }

        // question type default options
        $types = ['MCQ - Single Answer', 'MCQ - Multiple Answer', 'Short Answer', 'Long Answer', 'Upload Type Question'];

        foreach($types as $type) {
            Type::firstOrCreate(['type' => $type]);
        }

    }
}
