<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;

class LoginTest extends TestCase
{

    // use RefreshDatabase;
    public function test_register(): void
    {

        User::truncate();
        $user = User::factory()->make();
        $data = [
            'name' => 'RAJDEEP',
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
            'status' => '1',
            'email_verified_at' => $user->email_verified_at,
            'remember_token' => $user->remember_token,
        ];


        $response = $this->post('/api/auth/register', $data);
        // fwrite(STDERR, print_r($response->getContent(), TRUE));

        $response->assertStatus(200);
        // $this->assertDatabaseHas('users', [
        //     'email' => $data['email'],
        //     'email_verified_at' => $data['email_verified_at'],
        //     'remember_token' => $data['remember_token'],
        // ]);
    }

    public function test_login(): void
    {
        // Create a user with the specified password
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        // Send a POST request to the login endpoint with the user's credentials
        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
            'status' => 1
        ]);

        // Print response content for debugging (optional)
        // fwrite(STDERR, print_r($response->getContent(), TRUE));

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the structure of the response
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'status',
                'created_at',
                'updated_at',
                'roleid',
            ],
        ]);

    }


}
