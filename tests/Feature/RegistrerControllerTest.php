<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
class RegistrerControllerTest extends TestCase
{
    use withFaker;

    private $user;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'nickname'=>'bixito',
            'email' => 'tbixibiciest@example.com',
            'password' => bcrypt('password123.'),
        ]);
    }
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
    public function testExistigMail()
    {
        $userData = [
            'nickname'=> 'xuxuru',
            'email' => $this->user->email,
            'password' => 'ValidPassword123.',
        ];

        $response = $this->createPlayerRequest($userData);

        $response->assertStatus(400)
            ->assertJsonValidationErrors(['email']);
    }
    public function testExistingNickname()
    {
        $userData = [
            'nickname'=> $this->user->nickname,
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
    public function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }
}
