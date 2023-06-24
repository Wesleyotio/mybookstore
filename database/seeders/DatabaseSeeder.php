<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Database\Factories\BookFactory;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(10)->create();
        Book::factory()->count(10)->create();

    }
}
