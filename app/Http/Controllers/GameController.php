<?php

namespace App\Http\Controllers;

use App\Classes\State;
use App\Events\PlayerJoined;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
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
            'game' => $game,
            'state' => $game->state,
            'maxPlayers' => $game->max_players,
            'started' => $game->started,
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

        $state = new State($game->state);
        $state->addPlayer($user->id, $user->name);
        $game->state = json_encode($state);
        $game->save();
        $game->users()->save($user);

        broadcast(new PlayerJoined($game->id, $state))->toOthers();

        return redirect('game/'.$game->id);
    }

    public function playerChangePlayingState(Request $request) {
        $userId = $request->input('user_id');
        $gameId = $request->input('game_id');
        $isPlaying = $request->input('isPlaying');

        DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->update([
            'playing' => $isPlaying
        ]);

        return response()->noContent(200);
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

    public function listGamesUser() {
        $user = Auth::user();

        $joinedGames = DB::table('game_user')->where('user_id', $user->id)->value('game_id');

        $games = Game::withCount(['users' => function (Builder $query) {
            $query->where('playing', '=', true);
        }])->where([
            ['id', '=', $joinedGames],
        ])->get();

        return view(
            'user-game-list',
            [
                'games' => $games
            ]
        );
    }
}
