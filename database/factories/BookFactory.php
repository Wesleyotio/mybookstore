<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{

    protected $model = Book::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        

        return [
            'status' => 'active',
            'name' => $this->faker->sentence(5, true),
            'ISBN' => $this->faker->randomNumber(6, false),
            'value' => $this->faker->randomFloat(4, 20, 100),

        ];
    }
}
