<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $authors = [
            'J.K. Rowling',
            'George Orwell',
            'Jane Austen',
            'Mark Twain',
            'Agatha Christie',
            'William Shakespeare',
            'Leo Tolstoy',
            'Stephen King',
        ];

        foreach ($authors as $name) {
            Author::create(['name' => $name]);
        }
    }
}
