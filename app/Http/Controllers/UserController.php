<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class UserController extends Controller
{

  public function createPlayer(Request $request)
 {
      $validator = Validator::make($request->all(), [
        'nickname' => 'nullable|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],

    ]);


      if ($validator->fails()) {
          return response()->json(['errors' => $validator->errors()], 400);
      }

      $user = User::create([
        'nickname' => $request->has('nickname') ? $request->nickname : 'Anònim',
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);
      $user->assignRole('player');

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

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Correu o contrassenya incorrectes'], 401);
    }

    $token = $user->createToken('MyAppToken')->accessToken;
    return response()->json(['access_token' => $token, 'message' => 'Sessió oberta'], 200);
}

    public function logout(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->tokens->each(function ($token, $key) {
                $token->delete();

            });

            return response()->json(['message' => 'sessió tancada' ]);
        }
    }
    public function editPlayer(Request $request, $id) {

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Jugador no trobat'], 404);
        }
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id != $id) {
            return response()->json(['message' => 'No tens permisos per editar aquest jugador'], 403);
        }



      $request->validate([
        'nickname' => 'nullable|string|max:255|unique:users,nickname,'
    ]);
      $user->nickname = $request->input('nickname');
      $user->save();

      return response()->json(['message' => 'Nom del jugador editat'], 200);
    }
    public function players()
   {
    $users = User::all();


    $response = $users->map(function ($user) {
        return [
            'id' => $user->id,
            'nickname' => $user->nickname,
        ];
    });

    return response()->json(['players' => $response], 200);
}

    public function playersList($id)
    {
      $player = User::find($id);

      if (!$player) {
        return response()->json(['message' => 'Jugador no trobat'], 404);
    }
    if (auth()->check()) {
        $authenticatedUser = auth()->user();

        if ($authenticatedUser->id !== $player->id) {
            return response()->json(['error' => 'Només pots mirar les teves jugades..'], 403);
        }
    }
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

        if ($players->isEmpty()) {
            return response()->json(['message' => 'No hi ha jugadors'], 404);
        }

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

    public function worstPlayer()
    {
        $players = User::all();

    if ($players->isEmpty()) {
        return response()->json(['message' => 'No hi ha jugadors'], 404);
    }

    $worstPlayers = [];
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
            $worstPlayers = [$player];
            $lowestWinrate = $winrate;
        } elseif ($winrate == $lowestWinrate) {
            $worstPlayers[] = $player;
        }
    }

    $worstPlayerNicknames = collect($worstPlayers)->pluck('nickname')->all();
    if ($worstPlayerNicknames == NULL) {
        return response()->json(['message' => 'No hi ha jugadors'], 404);
    }
    return [
        'Worst Player' => $worstPlayerNicknames,
        'Worst Winrate' => $lowestWinrate,
    ];
}

    public function bestPlayer()
    {
      $players = User::all();

      if ($players->isEmpty()) {
        return response()->json(['message' => 'No hi ha jugadors'], 404);
    }

      $bestPlayers = [];
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
              $bestPlayers = [$player];
              $bestWinrate = $winrate;
          }
          elseif ($winrate == $bestWinrate) {
            $bestPlayers[] = $player;
        }
      }
      $bestPlayersNicknames = collect($bestPlayers)->pluck('nickname')->all();

      if ($bestPlayersNicknames == NULL) {
        return response()->json(['message' => 'No hi ha jugadors'], 404);
    }

      return [
        'Best Player' =>$bestPlayersNicknames,
        'Best Winrate' => $bestWinrate,
        ];

    }
  }
