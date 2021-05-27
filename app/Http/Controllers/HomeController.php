<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

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
        $type = $request->input('gameType');

        $game = new Game();


        switch ($type) {
            case 1:
                $game->max_players = 2;

                break;

            default:
                # code...
                break;
        }
        $game->save();

        return 'test';
    }
}
