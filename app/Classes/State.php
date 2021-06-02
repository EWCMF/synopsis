<?php

namespace App\Classes;

use App\Models\Card;
use Illuminate\Support\Facades\DB;
use JsonSerializable;

class State implements JsonSerializable
{
    private $ownerId;

    private $players = array();

    private $playDeck = array();
    private $techDeck = array();
    private $plotDeck = array();
    private $resourceDeck = array();
    private $populationDeck = array();
    private $discardPile = array();

    private $cardsOnHand = array();
    private $currentTurn;
    private int $turnSequence;

    private $purchaseablePlots = array();
    private $purchaseableTech = array();

    private $attacking;
    private $defending;

    private int $winnerId;

    private int $maxHappinessPlayerId;
    private int $maxCulturePlayerId;

    public function __construct($json = null)
    {
        if ($json == null) {
            $this->newState();
        } else {
            $this->set(json_decode($json, true));
        }
    }

    public function addPlayer($playerId, $playerName)
    {
        array_push($this->players, [
            'id' => $playerId,
            'name' => $playerName
        ]);
    }

    public function startGame()
    {
        shuffle($this->playDeck);
        shuffle($this->playDeck);
        shuffle($this->techDeck);
        shuffle($this->techDeck);
        shuffle($this->plotDeck);
        shuffle($this->plotDeck);

        shuffle($this->players);
        $this->currentTurn = $this->players[1];
        $this->turnSequence = 5;

        foreach ($this->players as $player) {
            $this->cardsOnHand[$player['id']]['plots'] = array();
            $this->cardsOnHand[$player['id']]['techs'] = array();
            $this->cardsOnHand[$player['id']]['resources'] = array();
            $this->cardsOnHand[$player['id']]['hand'] = array();

            $this->cardsOnHand[$player['id']]['plots'] = [
                array_pop($this->plotDeck),
                array_pop($this->plotDeck),
            ];
        }

        $this->purchaseablePlots = [
            array_pop($this->plotDeck),
            array_pop($this->plotDeck),
        ];
    }

    public function pickCard($cardIndex, $deck, $userId) {
        if ($userId != $this->currentTurn['id']) {
            return false;
        }

        switch ($deck) {
            case 'purchaseablePlots':
                $card = $this->purchaseablePlots[$cardIndex];
                array_push($this->cardsOnHand[$userId]['plots'], $card);
                if ($this->turnSequence == 5) {
                    $this->checkStartingPlots();
                }
                return true;
            default:
                # code...
                break;
        }
    }

    public function checkStartingPlots() {
        $lastPlayer = end($this->players);
        foreach ($this->players as $player) {
            if ($player['id'] == $this->currentTurn['id']) {
                if ($lastPlayer == $player) {
                    $this->currentTurn = $this->players[0];
                } else {
                    $index = array_search($player, $this->players) + 1;
                    $this->currentTurn = $this->players[$index];
                }
            }
        }

        if (empty($this->purchaseablePlots)) {
            $this->turnSequence = 1;
        }
    }

    public function newState()
    {
        $plotId = DB::table('card_types')->where('name', 'Plot')->value('id');
        $resourceId = DB::table('card_types')->where('name', 'Resource')->value('id');
        $populationId = DB::table('card_types')->where('name', 'Population')->value('id');
        $technologyId = DB::table('card_types')->where('name', 'Technology')->value('id');
        $unitId = DB::table('card_types')->where('name', 'Unit')->value('id');
        $buildingId = DB::table('card_types')->where('name', 'Building')->value('id');
        $wonderId = DB::table('card_types')->where('name', 'Wonder')->value('id');
        $bonusId = DB::table('card_types')->where('name', 'Bonus resource')->value('id');

        $techCards = Card::where('card_type_id', $technologyId)->get();
        foreach ($techCards as $techCard) {
            $json = json_decode($techCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->techDeck,
                    new TechnologyCard(
                        $json->name,
                        $json->cost,
                        $json->specialEffect,
                        $json->specialEffectId,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $plotCards = Card::where('card_type_id', $plotId)->get();
        foreach ($plotCards as $plotCard) {
            $json = json_decode($plotCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->plotDeck,
                    new PlotCard(
                        $json->name,
                        $json->specialEffect,
                        $json->specialEffectId,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $resourceCards = Card::where('card_type_id', $resourceId)->get();
        foreach ($resourceCards as $resourceCard) {
            $json = json_decode($resourceCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->resourceDeck,
                    new ResourceCard(
                        $json->name,
                        $json->resource,
                        $json->count,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $populationCards = Card::where('card_type_id', $populationId)->get();
        foreach ($populationCards as $populationCard) {
            $json = json_decode($populationCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->populationDeck,
                    new PopulationCard(
                        $json->name,
                        $json->specialEffect,
                        $json->specialEffectId,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $buildingCards = Card::where('card_type_id', $buildingId)->get();
        foreach ($buildingCards as $buildingCard) {
            $json = json_decode($buildingCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->playDeck,
                    new BuildingCard(
                        $json->name,
                        $json->cost,
                        $json->specialEffect,
                        $json->specialEffectId,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $wonderCards = Card::where('card_type_id', $wonderId)->get();
        foreach ($wonderCards as $wonderCard) {
            $json = json_decode($wonderCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->playDeck,
                    new WonderCard(
                        $json->name,
                        $json->cost,
                        $json->specialEffect,
                        $json->specialEffectId,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $unitCards = Card::where('card_type_id', $unitId)->get();
        foreach ($unitCards as $unitCard) {
            $json = json_decode($unitCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->playDeck,
                    new UnitCard(
                        $json->name,
                        $json->specialEffect,
                        $json->specialEffectId,
                        $json->maxCardsInDeck
                    )
                );
            }
        }

        $bonusCards = Card::where('card_type_id', $bonusId)->get();
        foreach ($bonusCards as $bonusCard) {
            $json = json_decode($bonusCard->properties);
            for ($i = 0; $i < $json->maxCardsInDeck; $i++) {
                array_push(
                    $this->playDeck,
                    new BonusResourceCard(
                        $json->name,
                        $json->resource,
                        $json->count,
                        $json->isWild,
                        $json->maxCardsInDeck
                    )
                );
            }
        }
    }

    public function set($data)
    {
        foreach ($data as $key => $value) $this->{$key} = $value;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function setOwnerId($userId)
    {
        $this->ownerId = $userId;
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getCurrentTurn()
    {
        return $this->currentTurn;
    }
}
