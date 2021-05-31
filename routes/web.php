<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Class\WebSocketHandler;
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
Route::get('/debug-state/{id}', [HomeController::class, 'debugState']);
