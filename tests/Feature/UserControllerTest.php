<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
class UserControllerTest extends TestCase
{
    private $user;
    private $user2;
    private $authController;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'nickname'=>'bixito',
            'email' => 'tbixibiciest@example.com',
            'password' => bcrypt('password123.'),
        ]);
        $this->user2 = User::factory()->create();
    }
    public function testLoginOk()
    {
        $response = $this->json('POST','/api/login',[
            'email' => 'tbixibiciest@example.com',
            'password' => 'password123.',
        ]);

        $this->assertEquals(200, $response->getStatusCode());
        $responseData = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('access_token', $responseData);
    }
    public function testLoginNoOk()
    {
        $response = $this->json('POST','/api/login',[
            'email' => 'tbixibiciest@example.com',
            'password' => 'invalidpassword',
        ]);


        $this->assertEquals(401, $response->getStatusCode());

        $responseContent = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $responseContent);
        $this->assertEquals('Correu o contrassenya incorrectes', $responseContent['error']);
    }
     public function testEditPlayerSuccess()
     {
        $token = $this->user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('PUT', "/api/players/{$this->user->id}",
         ['nickname' => 'guapaxula.']);
         $this->assertEquals(200, $response->getStatusCode());
     }
     public function testEditPlayerNoUser()
     {
        $token = $this->user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('PUT', "/api/players/{90}",
         ['nickname' => 'keeee.']);
         $this->assertEquals(404, $response->getStatusCode());
     }

     public function testEditPlayerRepeatNickname()
     {
        $token = $this->user->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('PUT', "/api/players/{$this->user->id}",
         ['nickname' => 'bixito']);
         $this->assertEquals(422, $response->getStatusCode());
     }

    public function tearDown(): void
    {
        $this->user->delete();
        $this->user2->delete();

        parent::tearDown();
    }
}
