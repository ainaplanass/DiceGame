<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\HomeController;


Route::post('/players', [UserController::class, 'createPlayer']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:api')->group(function () {
       Route::post('/logout', [UserController::class, 'logout']);
        Route::put('players/{id}', [UserController::class, 'editPlayer']);
        Route::post('players/{id}/games', [GameController::class, 'throwDice']);
        Route::delete('players/{id}/games', [GameController::class, 'deleteGame']);
        Route::get('players/{id}/games', [UserController::class, 'playersList']);
        Route::get('players', [UserController::class, 'playersWinrate'])->middleware('can:playerswinrate');
        Route::get('players/ranking', [UserController::class, 'playersRanking'])->middleware('can:playersranking');
        Route::get('players/ranking/worst', [UserController::class, 'worstPlayer'])->middleware('can:worstplayer');
        Route::get('players/ranking/best', [UserController::class, 'bestPlayer'])->middleware('can:bestplayer');
    });
