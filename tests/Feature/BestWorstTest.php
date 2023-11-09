<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Game;
class BestWorstTest extends TestCase
{
    protected $createdPlayerIds = [];
    private $players;
    private $game;
    public function setUp(): void
    {
        parent::setUp();

       $this->players = User::factory()->count(3)->create();

       $this->createdPlayerIds = $this->players->pluck('id')->toArray();

       Game::factory()->create(['user_id' => $this->players[0],'result' => 2,]);
       Game::factory()->create(['user_id' => $this->players[2],'result' => 2,]);
       Game::factory()->create(['user_id' => $this->players[1],'result' => 7,]);
       Game::factory()->create(['user_id' => $this->players[1],'result' => 2,]);
    }
   public function testWorstPlayer(){

    $token = $this->players[0]->createToken('TestToken')->accessToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('get', "api/players/ranking/worst", []);
           $response->assertStatus(200);
       $response->assertJsonFragment([
           'Worst Player' => [$this->players[0]->nickname, $this->players[2]->nickname],
           'Worst Winrate' => 0,
       ]);
    }
   public function testBestPlayer(){

    $token = $this->players[0]->createToken('TestToken')->accessToken;

    $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])->json('get', "api/players/ranking/best", []);
         $response->assertStatus(200);
         $response->assertJsonFragment([
             'Best Player' => [$this->players[1]->nickname],
             'Best Winrate' => 50,
         ]);
   }
    // public function testNoPlayers(){ for testing this i disabled all the users creations and other testing functions

    //     $response = $this->json('get', "api/players/ranking/loser", []);
    //     $response->assertStatus(404);
    // }
    public function tearDown():void
    {
        User::whereIn('id', $this->createdPlayerIds)->delete();
        Game::whereIn('user_id', $this->createdPlayerIds)->delete();
        parent::tearDown();
    }};
