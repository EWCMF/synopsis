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

    public function listGames()
    {
        $user = Auth::user();

        $joinedGames = DB::table('game_user')->where('user_id', $user->id)->value('game_id');

        $games = Game::where([
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
