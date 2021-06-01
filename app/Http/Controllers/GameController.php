<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $user = Auth::user();

        $allowed = DB::table('game_user')->where([
            'user_id' => $user->id,
            'game_id' => $id,
        ])->exists();

        if (!$allowed) {
            return 'Unauthorized access';
        }

        $game = Game::find($id);

        return view('game', [
            'id' => $id,
            'game' => $game
        ]);
    }

    public function joinGame($id) {
        $user = Auth::user();

        $alreadyJoined = DB::table('game_user')->where([
            'user_id' => $user->id,
            'game_id' => $id,
        ])->exists();

        if ($alreadyJoined) {
            return 'Game already joined';
        }

        $game = Game::find($id);

        if ($game->started) {
            return 'Game already started';
        }

        $currentPlayers = DB::table('game_user')->where('game_id', '=', $id)->count();

        if ($currentPlayers >= $game->max_players) {
            return 'Game is full';
        }

        $game->users()->save($user);
        return redirect('game/'.$game->id);
    }

    public function listGames()
    {
        $user = Auth::user();

        $joinedGames = DB::table('game_user')->where('user_id', $user->id)->value('game_id');

        $games = Game::withCount('users')->where([
            ['started', '=', false],
            ['id', '!=', $joinedGames],
        ])->get();

        return view(
            'game-list',
            [
                'games' => $games
            ]
        );
    }
}
