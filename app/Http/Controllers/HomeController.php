<?php

namespace App\Http\Controllers;

use App\Classes\State;
use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function newGame(Request $request) {
        $user = Auth::user();
        $type = $request->input('gameType');

        $game = new Game();

        $state = new State();
        $state->addPlayer($user->id, $user->name);
        $state->setOwnerId($user->id);

        switch ($type) {
            case 1:
                $game->max_players = 2;
                break;
            case 2:
                $game->max_players = 1;
                $state->addPlayer('CPU', 'CPU player');
                break;
            default:
                # code...
                break;
        }

        $game->state = json_encode($state);
        $game->save();
        $game->users()->save($user);
        return redirect('game/'.$game->id);
    }

    public function debugState($id) {
        $game = Game::find($id);

        $state = new State($game->state);
        $state->startGame();

        dd($state);
    }

    public function debugView() {
        return view('debug2');
    }
}
