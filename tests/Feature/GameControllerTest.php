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
    
    public function testThrowDice()
    {
        $user = User::factory()->create();
        $controller = new GameController();
        $response = $controller->throwDice($user->id);

        $this->assertEquals(201, $response->getStatusCode());

        $this->assertDatabaseHas('games', [
            'user_id' => $user->id,
        ]);
    }
      public function testDeleteGame()
      {
        $user = User::factory()->create();
        $game1 = Game::factory()->create(['user_id' => $user->id]);
        $game2 = Game::factory()->create(['user_id' => $user->id]);
        $controller = new GameController(); 
    
        $response = $controller->deleteGame($user->id);
    
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseMissing('games', ['user_id' => $user->id]);
      }
}
