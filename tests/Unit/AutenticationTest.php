<?php

namespace Tests\Unit;

use App\Models\User;
use Faker\Factory;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;

class AutenticationTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    use RefreshDatabase;


    public function test_users_can_authenticate_using_the_login_json() {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success'   => true,
                    'message'   => "Usuário logado com sucesso"
                ]);
    }

    public function test_users_can_not_authenticate_with_invalid_password() {
        $user = User::factory()->create();

        $response = $this->post('api/login', [
            'email' => $user->email,
            'password' => Hash::make('87654321'),
        ]);

        $response->assertStatus(401)
                ->assertJson([
                    'success'   => false,
                    'message'   => "email ou senha inválidos"
                ]);
    }

    public function test_users_can_logout_using_json() {
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);

        $response = $this->post('/api/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'success'   => true,
                    'message'   => "Usuário deslogado com sucesso"
                ]);


    }

    public function test_update_info_of_user(){
        $user = User::factory()->create();

        $response = $this->post('/api/login', [
            'email' => $user->email,
            'password' => '12345678',
        ]);
        $response = $this->post('/api/user/update', [
            'email' => 'jaosilva@teste.com',
            'name' => 'Jao Tester',
        ]);

        $response->assertJson([
            'success' => 'true',
            'message' => 'Usuário atualizado com sucesso'
        ]);
    }

    public function test_register_user(){

        $faker = Factory::create();
        $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\Address($faker));
        $faker->addProvider(new \Faker\Provider\pt_BR\Company($faker));
        $faker->addProvider(new \Faker\Provider\Internet($faker));

        $response = $this->post('/api/register', [
            'name' =>  $faker->name,
            'cpf' => $faker->cpf,
            'email' => $faker->freeEmail,
            'address' => $faker->address,
            'password' => '12345678', // password

        ]);

        $response->assertStatus(200)
                ->assertJson([
                    'success'   => true,
                    'message'   => "Usuário registrado e logado com sucesso"
                ]);
    }

}
