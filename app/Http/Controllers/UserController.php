<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UserController extends Controller
{
  public function index()
  {
      return view ('holaaaa');
  }
  public function createPlayer(Request $request)
 {
      $validator = Validator::make($request->all(), [
        'nickname' => 'nullable|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:6',
    ]);


      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 400);
      }

      $user = User::create([
        'nickname' => $request->has('nickname') ? $request->nickname : 'Anònim',
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]); 

      return response()->json(['message' => 'Usuari registrat amb èxit'], 201);
  }

  public function login(Request $request)
  {
      $validator = Validator::make($request->all(), [
          'email' => 'required|string|email',
          'password' => 'required|string',
      ]);

      if ($validator->fails()) {
          return response()->json(['error' => $validator->errors()], 400);
      }

      $credentials = $request->only(['email', 'password']);

      if (auth()->attempt($credentials)) {
      $user = auth()->user();
        $token = $user->createToken('MyAppToken')->accessToken;
        return response()->json(['access_token' => $token], 200);
      }

      return response()->json(['error' => 'Credencials invàlides'], 401);
    }
    public function editPlayer(Request $request, $id) {
      $user = User::find($id);

      if (!$user) {
          return response()->json(['message' => 'Jugador no trobat'], 404);
      }

      $user->nickname = $request->input('nickname');
      $user->save();

      return response()->json(['message' => 'Nom del jugador editat'], 200);
    }

    public function playersWinrate()
  {
      $players = User::all();

      $playersWithWinrate = $players->map(function ($player) {
          $totalGames = $player->games->count();
          $totalWins = $player->games->where('result', 7)->count();

          if ($totalGames > 0) {
              $winRate = ($totalWins / $totalGames) * 100;
          } else {
              $winRate = 0;
          }

          return [
              'nickname' => $player->nickname,
              'winrate' => $winRate,
          ];
      });

    return response()->json(['players' => $playersWithWinrate], 200);
  }
    public function playersList($id)
    {
      $player = User::find($id);
      $games = $player->games->map(function ($game) {
        return [
            'dice1' => $game->dice1,
            'dice2' => $game->dice2,
            'result' => $game->result,
        ];
    });
  
      return response()->json(['games'=>$games], 200);

    }
    public function playersRanking()
    {
        $players = User::all();
    
        $totalGames = 0;
        $totalWins = 0;
    
        foreach ($players as $player) {
            $totalGames += $player->games->count();
            $totalWins += $player->games->where('result', 7)->count();
        }
    
        if ($totalGames > 0) {
            $winrate = ($totalWins / $totalGames) * 100;
        } else {
            $winrate = 0;
        }
    
        return response()->json(['Winrate general' => $winrate], 200);
    }
    
    public function worstPlayers()
    {
        $players = User::all();
    
        $worstPlayer = null;
        $lowestWinrate = 100; 
    
        foreach ($players as $player) {
            $totalGames = $player->games->count();
            $totalWins = $player->games->where('result', 7)->count();
    
            if ($totalGames > 0) {
                $winrate = ($totalWins / $totalGames) * 100;
            } else {
                $winrate = 0;
            }
    
            if ($winrate < $lowestWinrate) {
                $lowestWinrate = $winrate;
                $worstPlayer = $player;
            }
        }

        return [
          'Worst Player' => $worstPlayer->nickname,
          'Worst Winrate' => $lowestWinrate,
    ];
    
    }
    
    public function bestPlayers()
    {
  
      $players = User::all();
    
      $bestPlayer = null;
      $bestWinrate = 0; 
  
      foreach ($players as $player) {
          $totalGames = $player->games->count();
          $totalWins = $player->games->where('result', 7)->count();
  
          if ($totalGames > 0) {
              $winrate = ($totalWins / $totalGames) * 100;
          } else {
              $winrate = 0;
          }
  
          if ($winrate > $bestWinrate) {
              $bestWinrate = $winrate;
              $bestPlayer = $player;
          }
      }

      return [
        'Worst Player' => $bestPlayer->nickname,
        'Worst Winrate' => $bestWinrate,
  ];
  
    }
  }
