<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;


Route::post('/players', [UserController::class, 'createPlayer']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:api')->group(function () {
        Route::put('players/{id}', [UserController::class, 'editPlayer']);
        Route::post('players/{id}/games', [GameController::class, 'throwDice']);
        Route::delete('players/{id}/games', [GameController::class, 'deleteGame']);
        Route::get('players', [UserController::class, 'playersWinrate']);
        Route::get('players/{id}/games', [UserController::class, 'playersList']);
        Route::get('players/ranking', [UserController::class, 'playersRanking']);
        Route::get('players/ranking/loser', [UserController::class, 'worstPlayers']);
        Route::get('players/ranking/winner', [UserController::class, 'bestPlayers']);
 });
