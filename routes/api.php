<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;


Route::post('/players', [UserController::class, 'createPlayer']);
Route::post('/login', [UserController::class, 'login']);

    // Route::middleware('auth:api')->group(function () {
        Route::put('players/{id}', [UserController::class, 'editPlayer'])->middleware('can:editplayer');
        Route::post('players/{id}/games', [GameController::class, 'throwDice'])->middleware('can:throwdice');
        Route::delete('players/{id}/games', [GameController::class, 'deleteGame'])->middleware('can:deletegame');
        Route::get('players', [UserController::class, 'playersWinrate'])->middleware('can:teams.store');;
        Route::get('players/{id}/games', [UserController::class, 'playersList'])->middleware('can:playerslist');
        Route::get('players/ranking', [UserController::class, 'playersRanking'])->middleware('can:playersranking');
        Route::get('players/ranking/loser', [UserController::class, 'worstPlayers'])->middleware('can:worstplayer');
        Route::get('players/ranking/winner', [UserController::class, 'bestPlayers'])->middleware('can:bestplayer');;
// });
