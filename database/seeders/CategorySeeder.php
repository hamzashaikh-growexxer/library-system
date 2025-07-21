<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'Fiction',
            'Non-fiction',
            'Science Fiction',
            'Fantasy',
            'Mystery',
            'Romance',
            'Biography',
            'History',
            'Horror',
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }
    }
}
