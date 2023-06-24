<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\User;
use Faker\Factory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;


class BookTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_all_books()
    {
        $user = User::factory()->create();
        $books = Book::factory()->count(15)->create()->toArray();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];
        $responseBook = $this->get('/api/books/all', [
            "Accept"=>"application/json",
            'Authorization' => 'Bearer' . $token,
        ]);

        $responseBook->assertStatus(200)
                    ->assertJson([
                        'success' => true,
                        'books' => $books,
                    ]);

    }

    public function test_create_book()
    {
        $user = User::factory()->create();
        $faker = Factory::create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];
        $responseBook = $this->post('/api/books/create',
                [
                    'status' => 'active',
                    'name' => $faker->sentence(5, true),
                    'ISBN' => $faker->randomNumber(6, false),
                    'value' => $faker->randomFloat(2, 20, 100)
                ],
            [
                "Accept"=>"application/json",
                'Authorization' => 'Bearer' . $token,
            ]);

        $responseBook->assertStatus(200)
                    ->assertJson([
                        'success' => true,
                        'message' => 'livro criado com sucesso'
                    ]);
    }
    public function test_update_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $faker = Factory::create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];
        $responseBook = $this->post('/api/books/update/'. $book->id,
                [
                    'status' => 'active',
                    'name' => $faker->sentence(5, true),
                    'ISBN' => $faker->randomNumber(6, false),
                    'value' => $faker->randomFloat(2, 20, 100)
                ],
            [
                "Accept"=>"application/json",
                'Authorization' => 'Bearer' . $token,
            ]);

        $responseBook->assertStatus(200)
                    ->assertJson([
                        'success' => true,
                        'message' => 'Livro atualizado com sucesso'
                    ]);
    }

    public function test_delete_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();
        $faker = Factory::create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $token = $response['token'];
        $responseBook = $this->delete('/api/books/destroy/'. $book->id,

            [
                "Accept"=>"application/json",
                'Authorization' => 'Bearer' . $token,
            ]);

        $responseBook->assertStatus(200)
                    ->assertJson([
                        'success' => true,
                        'message' => 'Livro removido com sucesso'
                    ]);
    }
}
