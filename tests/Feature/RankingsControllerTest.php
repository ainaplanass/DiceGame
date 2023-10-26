<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
class RankingsControllerTest extends TestCase
{   
    protected $createdPlayerIds = [];
    private $players;
    public function setUp(): void
    {
        parent::setUp();

        $this->players = User::factory()->count(3)->create();

        $this->createdPlayerIds = $this->players->pluck('id')->toArray();

        foreach ($this->players as $player) {
            Game::factory()->count(5)->create([
                'user_id' => $player->id,
                'result' => 7,
            ]);
        }

    }
    public function testPlayersWinrate()
    {
        $response =  $this->json('get', "/api/players", []);

        foreach ($response['players'] as $playerData) {
            $this->assertEquals(100, $playerData['winrate']);
        }
    }
    public function testUserWinrate()
    {
        $firstplayer = $this->players[0]->id;
        $response =  $this->json('get', "/api/players/{$firstplayer}/games", []);
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('games', $responseData);
        $this->assertIsArray($responseData['games']);
    }

    public function tearDown():void
    {
        User::whereIn('id', $this->createdPlayerIds)->delete();
        Game::whereIn('user_id', $this->createdPlayerIds)->delete();
        parent::tearDown();
    }}
