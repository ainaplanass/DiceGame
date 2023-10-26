<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Psy\Readline\Userland;

class GameController extends Controller
{
    public function throwDice($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Jugador no trobat'], 404);
        }
        $dice1 = random_int(1, 6);
        $dice2 = random_int(1, 6);

        $total = $dice1 + $dice2;

        $game =  Game::create([
            'dice1' => $dice1,
            'dice2' => $dice2,
            'result' => $total,
            'user_id' => $user->id, 
        ]);

        $game->save();

        return response()->json(['message' => 'Tirada registrada con éxito'], 201);
    }
    public function deleteGame($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Jugador no trobat'], 404);
        }

        $user->games()->delete();
        
        return response()->json(['message' => 'Tiradas del jugador eliminadas con éxito'], 200);

    }

}