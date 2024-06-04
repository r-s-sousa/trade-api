<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\TeamController;
use App\Http\Controllers\Api\V1\ChampionshipController;
use App\Http\Controllers\Api\V1\ChampionshipTeamController;
use App\Http\Controllers\Api\V1\GameController;

Route::post('/login', [AuthController::class, 'login'])
    ->name('login');
Route::post('/register', [AuthController::class, 'register'])
    ->name('register');

Route::post('/refresh-token', [AuthController::class, 'refreshToken'])
    ->name('refreshToken')
    ->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth:sanctum');

Route::group([
    'prefix' => 'v1',
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['auth:sanctum']
], function () {
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('championships', ChampionshipController::class);

    Route::get('/championship-teams/{championship}/teams', [ChampionshipTeamController::class, 'index']);
    Route::get('/championship-teams/{championshipTeam}', [ChampionshipTeamController::class, 'show']);
    Route::post('/championship-teams', [ChampionshipTeamController::class, 'store']);
    Route::post('/championship-teams/bulk', [ChampionshipTeamController::class, 'bulkStore']);
    Route::match(['put', 'patch'], '/championship-teams/{championshipTeam}', [ChampionshipTeamController::class, 'update']);
    Route::delete('/championship-teams/{championshipTeam}', [ChampionshipTeamController::class, 'destroy']);

    Route::get('/games/{championship}/matches', [GameController::class, 'index']);
    Route::get('/games/{game}', [GameController::class, 'show']);
    Route::post('/games', [GameController::class, 'store']);
    Route::match(['put', 'patch'], '/games/{game}', [GameController::class, 'update']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);
});
