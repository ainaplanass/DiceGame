<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class RegistrerControllerTest extends TestCase
{
    use withFaker;
    public function testCreatePlayer()
    {  
        $userData = [
            'nickname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'ValidPassword12.!',
        ];

        $response = $this->createPlayerRequest($userData);

        $response->assertStatus(201)
            ->assertJson(['message' => 'Usuari registrat amb èxit']);
      
    }
    public function testInvalidEmail()
    {
        $userData = [
            'nickname' => $this->faker->name(),
            'email' => 'dafafsfdaikk',
            'password' => 'ValidPassword12.!',
        ];

        $response = $this->createPlayerRequest($userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['email']);
    }
    public function testInvalidPassword()
    {
        $userData = [
            'nickname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'ohno',
        ];

        $response = $this->createPlayerRequest($userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['password']);
    }
    public function testExistinMail()
    {
        $user = User::factory()->create();
        $userData = [
            'nickname'=> 'xuxuru',
            'email' => $user->email,
            'password' => 'ValidPassword123.',
        ];

        $response = $this->createPlayerRequest($userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['email']);
    }
    public function testExistinNickname()
    {
        $user = User::factory()->create();
        $userData = [
            'nickname'=> $user->nickname,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'ValidPassword123.',
        ];

        $response = $this->createPlayerRequest($userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['nickname']);
    }

    protected function createPlayerRequest($data)
    {
        return $this->postJson('/api/players', $data);
    }
}
