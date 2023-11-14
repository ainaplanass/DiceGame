<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
class RankingsTest extends TestCase
{
    protected $createdPlayerIds = [];
    private $players;
    private $game;
    public function setUp(): void
    {
        parent::setUp();

        $this->players = User::factory()->count(3)->create();
        $this->players[0]->assignRole('admin');
        foreach ($this->players as $player) {
            $player->assignRole('player');
        }


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
        $token = $this->players[0]->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('get', "/api/players", []);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'players' => [
                    '*' => [
                        'id',
                        'nickname',
                        'winrate',
                    ],
                ],
            ]);
    }
    public function testUserWinrate()
    {
        $firstplayer = $this->players[0]->id;
        $token = $this->players[0]->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('get', "/api/players/{$firstplayer}/games", []);
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertArrayHasKey('games', $responseData);
        $this->assertIsArray($responseData['games']);
    }

    public function testUserInvalidWinrate()
    {
        $token = $this->players[0]->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('get', "/api/players/{900}/games", []);
        $response->assertStatus(404);
    }
    public function testPlayersRanking()
    {
        $token = $this->players[0]->createToken('TestToken')->accessToken;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('get', "api/players/ranking", []);
        $response->assertStatus(200);

        $responseData = $response->json();

        $this->assertArrayHasKey('Winrate general', $responseData);

        $this->assertEquals(100, $responseData['Winrate general']);
    }

    public function tearDown():void
    {
        User::whereIn('id', $this->createdPlayerIds)->delete();
        Game::whereIn('user_id', $this->createdPlayerIds)->delete();
        parent::tearDown();
    }};
