<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Class\WebSocketHandler;
use App\Http\Controllers\GameController;
use BeyondCode\LaravelWebSockets\Facades\WebSocketsRouter;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/new-game', [HomeController::class, 'newGame']);
Route::get('/game/{id}', [GameController::class, 'index']);
Route::get('/join-game/{id}', [GameController::class, 'JoinGame']);
Route::get('/game-list', [GameController::class, 'listGames']);
Route::get('/user-game-list', [GameController::class, 'listGamesUser']);
Route::post('/change-playing-state', [GameController::class, 'playerChangePlayingState']);
Route::post('/start-game', [GameController::class, 'startGame']);
Route::post('/make-move', [GameController::class, 'makeMove']);
Route::get('/debug-state/{id}', [HomeController::class, 'debugState']);
Route::get('/debug-view', [HomeController::class, 'debugView']);
