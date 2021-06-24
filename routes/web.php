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
Route::post('/make-moves', [GameController::class, 'makeMoves']);
Route::post('/skip-move', [GameController::class, 'skipMove']);
Route::post('/request-current-view', [GameController::class, 'requestCurrentView']);
Route::post('/request-plot-modal', [GameController::class, 'requestPlotModal']);
Route::post('/request-plot-resource-modal', [GameController::class, 'requestPlotResourceModal']);
Route::post('/request-plot-purchase-modal', [GameController::class, 'requestPlotPurchaseModal']);
Route::post('/request-building-purchase-modal', [GameController::class, 'requestBuildingPurchaseModal']);
Route::post('/cpu-move', [GameController::class, 'cpuMove']);
Route::post('/add-resources', [GameController::class, 'addResources']);
Route::post('/purchase-plot', [GameController::class, 'plotPurchase']);
Route::get('/debug', [GameController::class, 'debug']);

