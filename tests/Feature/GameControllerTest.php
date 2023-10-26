<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\GameController;
class GameControllerTest extends TestCase
{

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
     public function testThrowDice()
     {
         $response = $this->json('POST', "/api/players/{$this->user->id}/games", []);

         $this->assertEquals(201, $response->getStatusCode());

         $this->assertDatabaseHas('games', [
             'user_id' => $this->user->id,
         ]);
     }
      public function testDeleteGame()
      {            
        $game1 = Game::factory()->create(['user_id' => $this->user->id]);
        $game2 = Game::factory()->create(['user_id' => $this->user->id]);
        $response = $this->json('delete', "/api/players/{$this->user->id}/games", []);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseMissing('games', ['user_id' => $this->user->id]);
      }

      public function tearDown(): void
    {
        $this->user->delete();

        parent::tearDown();
    }
}
