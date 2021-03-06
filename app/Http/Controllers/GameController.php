<?php

namespace App\Http\Controllers;

use App\Classes\State;
use App\Events\GameFinished;
use App\Events\GameStarted;
use App\Events\NewMove;
use App\Events\PlayerJoined;
use App\Events\PlayerSpecificInfo;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $state = new State($game->state);

        $viewProperties = [
            'id' => $id,
            'players' => $state->getPlayers(),
            'maxPlayers' => $game->max_players,
            'started' => $game->started,
            'userId' => $user->id,
            'ownerId' => $state->getOwnerId(),
            'deckLength' => count($state->getPlayDeck()),
            'discardPileLength' => count($state->getDiscardPile()),
            'purchaseablePlots' => $state->getPurchaseablePlots(),
            'purchaseableTechs' => $state->getPurchaseableTechs(),
            'attacking' => $state->getAttacking(),
            'defending' => $state->getDefending(),
            'currentTurn' => $state->getCurrentTurn(),
            'turnSequence' => $state->getTurnSequence(),
        ];

        $cardsInHand = $state->getCardsInHand();
        $playerNotes = $state->getPlayerNotes();
        switch ($game->max_players) {
            case 1:
                foreach (array_keys($cardsInHand) as $key) {
                    if ($key == $user->id) {
                        $viewProperties['ownHand'] = $cardsInHand[$key];
                        $viewProperties['notes'] = $playerNotes[$key];
                    } else {
                        $viewProperties['foeHand'] = $cardsInHand[$key];
                    }
                }

                return view('game', $viewProperties);
            case 2:
                foreach (array_keys($cardsInHand) as $key) {
                    if ($key == $user->id) {
                        $viewProperties['ownHand'] = $cardsInHand[$key];
                        $viewProperties['notes'] = $playerNotes[$key];
                    } else {
                        $viewProperties['foeHand'] = $cardsInHand[$key];
                    }
                }

                return view('game', $viewProperties);
            case 3:


            case 4:


            default:
                break;
        }


        return view('game', [
            'id' => $id,
            'players' => $state->getPlayers(),
            'maxPlayers' => $game->max_players,
            'started' => $game->started,
            'userId' => $user->id,
            'ownerId' => $state->getOwnerId()
        ]);
    }

    public function joinGame($id)
    {
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

        return redirect('game/' . $game->id);
    }

    public function startGame(Request $request)
    {
        $userId = $request->input('user_id');
        $gameId = $request->input('game_id');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $game = Game::find($gameId);

        $state = new State($game->state);
        $state->startGame();
        $game->state = json_encode($state);
        $game->started = true;
        $game->save();

        broadcast(new GameStarted($gameId, $state));

        return json_encode($state->getPlayerNotes()[$userId]);
    }

    public function addResources(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');
        $resources = $request->input('resources');

        $game = Game::find($gameId);
        $state = new State($game->state);

        if ($state->addResourceDistribution($userId, $resources)) {

            $game->state = json_encode($state);
            $game->save();

            broadcast(new NewMove($gameId, $state));
            foreach ($state->getPlayers() as $player) {
                broadcast(new PlayerSpecificInfo($player['id'], $state));
            }

            return response()->noContent(200);
        } else {
            return 'Error: not your turn';
        }
    }

    public function plotPurchase(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');
        $resources = $request->input('resources');
        $cardIndex = $request->input('cardIndex');

        $game = Game::find($gameId);
        $state = new State($game->state);

        if ($state->purchasePlot($userId, $cardIndex, $resources)) {
            $state->updateSpecialResources();

            $game->state = json_encode($state);
            $game->save();

            broadcast(new NewMove($gameId, $state));
            foreach ($state->getPlayers() as $player) {
                broadcast(new PlayerSpecificInfo($player['id'], $state));
            }

            return response()->noContent(200);
        } else {
            return 'Error: not your turn';
        }
    }


    public function makeMove(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');
        $cardIndex = $request->input('cardIndex');
        $deck = $request->input('deck');
        $option = $request->input('option');

        $game = Game::find($gameId);

        $state = new State($game->state);

        if ($state->pickCard($cardIndex, $deck, $userId, $option)) {
            $state->updateSpecialResources();

            $game->state = json_encode($state);
            $game->save();

            if ($state->checkVictory()) {
                broadcast(new GameFinished($gameId, $state));
                return response()->noContent(200);
            }

            broadcast(new NewMove($gameId, $state));
            foreach ($state->getPlayers() as $player) {
                broadcast(new PlayerSpecificInfo($player['id'], $state));
            }

            return response()->noContent(200);
        } else {
            return 'Error: not your turn';
        }
    }

    public function makeMoves(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');
        $cardIndexes = json_decode($request->input('cardIndexes'));
        $deck = $request->input('deck');

        $game = Game::find($gameId);

        $state = new State($game->state);

        if ($state->pickCards($cardIndexes, $deck, $userId)) {
            $state->updateSpecialResources();

            $game->state = json_encode($state);
            $game->save();

            broadcast(new NewMove($gameId, $state));
            foreach ($state->getPlayers() as $player) {
                broadcast(new PlayerSpecificInfo($player['id'], $state));
            }

            return response()->noContent(200);
        } else {
            return 'Error: not your turn';
        }
    }

    public function skipMove(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');

        $game = Game::find($gameId);

        $state = new State($game->state);

        if ($state->skipTurnSequence($userId)) {

            $game->state = json_encode($state);
            $game->save();

            broadcast(new NewMove($gameId, $state));

            return response()->noContent(200);
        } else {
            return 'Error: not your turn';
        }
    }

    public function requestCurrentView(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $game = Game::find($gameId);

        $state = new State($game->state);


        $viewProperties = [
            'id' => $gameId,
            'players' => $state->getPlayers(),
            'maxPlayers' => $game->max_players,
            'started' => $game->started,
            'userId' => $userId,
            'ownerId' => $state->getOwnerId(),
            'deckLength' => count($state->getPlayDeck()),
            'discardPileLength' => count($state->getDiscardPile()),
            'purchaseablePlots' => $state->getPurchaseablePlots(),
            'purchaseableTechs' => $state->getPurchaseableTechs(),
            'attacking' => $state->getAttacking(),
            'defending' => $state->getDefending(),
            'currentTurn' => $state->getCurrentTurn(),
            'turnSequence' => $state->getTurnSequence(),
        ];

        $cardsInHand = $state->getCardsInHand();
        switch ($game->max_players) {
            case 1:
                foreach (array_keys($cardsInHand) as $key) {
                    if ($key == $userId) {
                        $viewProperties['ownHand'] = $cardsInHand[$key];
                    } else {
                        $viewProperties['foeHand'] = $cardsInHand[$key];
                    }
                }

                return view('partials.game-area', $viewProperties);
                break;
            case 2:
                foreach (array_keys($cardsInHand) as $key) {
                    if ($key == $userId) {
                        $viewProperties['ownHand'] = $cardsInHand[$key];
                    } else {
                        $viewProperties['foeHand'] = $cardsInHand[$key];
                    }
                }

                return view('partials.game-area', $viewProperties);
                break;

            case 3:


            case 4:


            default:
                return response('Error', 404);
                break;
        }
    }

    public function requestPlotModal(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $state = new State(Game::find($gameId)->state);
        $cardsInHand = $state->getCardsInHand();

        $viewProperties = [
            'currentTurn' => $state->getCurrentTurn()['name'],
            'selectionPlots' => $state->getPurchaseablePlots(),
        ];

        foreach (array_keys($cardsInHand) as $key) {
            if ($key == $userId) {
                $viewProperties['ownPlots'] = $cardsInHand[$key]['plots'];
            } else {
                $viewProperties['foePlots'] = $cardsInHand[$key]['plots'];
            }
        }

        return view('modals.plot-modal-content', $viewProperties);
    }

    public function requestPlotResourceModal(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $state = new State(Game::find($gameId)->state);
        $hybridPlots = $state->getHybridPlotsForId($userId);

        $viewProperties = [
            'hybridPlots' => $hybridPlots
        ];

        return view('modals.plot-resource-dist', $viewProperties);
    }

    public function requestPlotPurchaseModal(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');
        $plotCardIndex = $request->input('cardIndex');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $state = new State(Game::find($gameId)->state);
        $resourcesAvailable = $state->getCardsInHandForUser($userId)['resources'];
        $needed = $state->getNeededResourcesForPlotForUser($userId);

        $viewProperties = [
            'resources' => $resourcesAvailable,
            'needed' => $needed,
            'plotCardIndex' => $plotCardIndex,
        ];

        return view('modals.plot-purchase-dist', $viewProperties);
    }

    public function requestBuildingPurchaseModal(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');
        $buildingIndex = $request->input('cardIndex');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $state = new State(Game::find($gameId)->state);
        $allowedPlots = $state->getPlotsOpenForBuildingsForUser($userId);

        $viewProperties = [
            'allowedPlots' => $allowedPlots,
            'buildingIndex' => $buildingIndex,
        ];

        return view('modals.plot-available', $viewProperties);
    }


    public function playerChangePlayingState(Request $request)
    {
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

    public function listGamesUser()
    {
        $user = Auth::user();

        $joinedGames = DB::table('game_user')->where('user_id', $user->id)->pluck('game_id')->toArray();

        $games = Game::whereIn('id', $joinedGames)->with(['users'])->get();


        return view(
            'user-game-list',
            [
                'games' => $games,
                'you' => $user->name,
            ]
        );
    }

    public function cpuMove(Request $request)
    {
        $userId = Auth::id();
        $gameId = $request->input('game_id');

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $game = Game::find($gameId);

        $state = new State($game->state);
        $state->cpuMove();

        $game->state = json_encode($state);
        $game->save();

        if ($state->checkVictory()) {
            broadcast(new GameFinished($gameId, $state));
            return response()->noContent(200);
        }

        broadcast(new NewMove($gameId, $state));
        foreach ($state->getPlayers() as $player) {
            broadcast(new PlayerSpecificInfo($player['id'], $state));
        }

        return response()->noContent(200);
    }

    public function debug()
    {
        $userId = Auth::id();
        $gameId = 34;

        $allowed = DB::table('game_user')->where([
            'user_id' => $userId,
            'game_id' => $gameId,
        ])->exists();

        if (!$allowed) {
            return response('Not authorized', 403);
        }

        $game = Game::find($gameId);

        $state = new State($game->state);

        return "test";
    }
}
