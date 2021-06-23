<?php

namespace App\Classes;

use App\Models\Card;
use Illuminate\Support\Facades\DB;
use JsonSerializable;

class State implements JsonSerializable
{
    private $ownerId;

    private $players = array();
    private $cpu = null;

    private $playDeck = array();
    private $techDeck = array();
    private $plotDeck = array();
    private $discardPile = array();

    private $playerNotes = array();
    private $cardsOnHand = array();
    private $currentTurn;
    private int $turnSequence;

    private $purchaseablePlots = array();
    private $purchaseableTechs = array();

    private $attacking = array();
    private $defending = array();

    private $winner;

    private int $maxHappinessPlayerId;
    private int $maxCulturePlayerId;

    private $currentMessageToLog;

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
        if ($playerId == 'CPU') {
            $this->cpu = new ComputerPlayer(rand(1, 5), rand(1, 5));
        }

        $player = [
            'id' => $playerId,
            'name' => $playerName,
        ];

        array_push($this->players, $player);

        $this->cardsOnHand[$player['id']]['plots'] = array();
        $this->cardsOnHand[$player['id']]['techs'] = array();
        $this->cardsOnHand[$player['id']]['resources'] = array(
            'food' => 0,
            'commerce' => 0,
            'production' => 0,
            'culture' => 0,
            'happiness' => 0,
            'victoryPoints' => 0,
        );
        $this->cardsOnHand[$player['id']]['hand'] = array();
        $this->cardsOnHand[$player['id']]['freePopUsed'] = false;
        $this->playerNotes[$player['id']] = array();
    }

    public function startGame()
    {
        shuffle($this->playDeck);
        shuffle($this->playDeck);
        shuffle($this->playDeck);
        shuffle($this->techDeck);
        shuffle($this->techDeck);
        shuffle($this->techDeck);
        shuffle($this->plotDeck);
        shuffle($this->plotDeck);
        shuffle($this->plotDeck);

        shuffle($this->players);
        $this->currentTurn = $this->players[0];
        $this->turnSequence = 5;

        foreach ($this->players as $player) {
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


    public function pickCard($cardIndex, $deck, $userId, $option)
    {
        if ($userId != $this->currentTurn['id']) {
            return false;
        }

        switch ($deck) {
            case 'purchaseablePlots':
                if ($this->turnSequence == 5) {
                    $card = array_splice($this->purchaseablePlots, $cardIndex, 1);
                    array_push($this->cardsOnHand[$userId]['plots'], $card[0]);
                    $this->checkStartingPlots();
                } else {
                    $this->currentMessageToLog = '';
                }

                return true;

            case 'purchaseableTech':
                if ($this->turnSequence == 2) {
                    $success = $this->purchaseTech($userId, $cardIndex);
                    if ($success) {
                        $this->currentMessageToLog = $this->currentTurn['name'] . ' has purchased technology and gained victory point';
                        return true;
                    } else {
                        return false;
                    }
                }

            case 'ownPlots':
                if ($this->turnSequence == 2) {
                    if ($this->checkCanPurchasePopulation($userId, $cardIndex)) {
                        $this->cardsOnHand[$userId]['plots'][$cardIndex]['attachedPopulation']++;
                        $this->currentMessageToLog = $this->currentTurn['name'] . ' purchased population for ' . $this->cardsOnHand[$userId]['plots'][$cardIndex]['name'];
                        return true;
                    }
                }

            case 'bonusResource':
                if ($this->turnSequence == 2) {
                    $this->currentMessageToLog = $this->currentTurn['name'] . " has gathered bonus resource.";
                    if ($option != 0) {
                        if ($option == 1) {
                            $specialEffectId = $this->cardsOnHand[$userId]['hand'][$cardIndex]['specialEffectId'];
                            if ($specialEffectId == 7) {
                                $this->cardsOnHand[$userId]['resources']['commerce'] += 1;
                            } else {
                                $this->cardsOnHand[$userId]['resources']['commerce'] += 2;
                            }
                        } else if ($option == 2) {
                            $specialEffectId = $this->cardsOnHand[$userId]['hand'][$cardIndex]['specialEffectId'];
                            if ($specialEffectId == 7) {
                                $this->cardsOnHand[$userId]['resources']['food'] += 1;
                            } else {
                                $this->cardsOnHand[$userId]['resources']['food'] += 2;
                            }
                        } else {
                            $specialEffectId = $this->cardsOnHand[$userId]['hand'][$cardIndex]['specialEffectId'];
                            if ($specialEffectId == 7) {
                                $this->cardsOnHand[$userId]['resources']['production'] += 1;
                            } else {
                                $this->cardsOnHand[$userId]['resources']['production'] += 2;
                            }
                        }

                        unset($this->cardsOnHand[$userId]['hand'][$cardIndex]);
                        $this->cardsOnHand[$userId]['hand'] = array_values($this->cardsOnHand[$userId]['hand']);
                        return true;
                    } else {
                        switch ($this->cardsOnHand[$userId]['hand'][$cardIndex]['specialEffectId']) {
                            case 1:
                                $this->cardsOnHand[$userId]['resources']['commerce'] += 2;
                                break;

                            case 2:
                                $this->cardsOnHand[$userId]['resources']['commerce'] += 3;
                                break;

                            case 3:
                                $this->cardsOnHand[$userId]['resources']['production'] += 2;
                                break;

                            case 4:
                                $this->cardsOnHand[$userId]['resources']['production'] += 3;
                                break;

                            case 5:
                                $this->cardsOnHand[$userId]['resources']['food'] += 2;
                                break;

                            case 6:
                                $this->cardsOnHand[$userId]['resources']['food'] += 3;
                                break;

                            default:
                                return false;
                        }

                        unset($this->cardsOnHand[$userId]['hand'][$cardIndex]);
                        $this->cardsOnHand[$userId]['hand'] = array_values($this->cardsOnHand[$userId]['hand']);
                        return true;
                    }
                }
            case 'building':
                $success = $this->purchaseBuilding($userId, $cardIndex, $option);
                if ($success) {
                    $this->currentMessageToLog = $this->currentTurn['name'] . ' has purchased a building for a plot';
                    return true;
                } else {
                    return false;
                }
        }

        return false;
    }

    public function pickCards($cardIndexes, $deck, $userId)
    {
        if ($userId != $this->currentTurn['id']) {
            return false;
        }

        switch ($deck) {
            case 'playDeck':
                switch ($this->turnSequence) {
                    case 1:
                        # code...
                        break;
                    case 2:
                        break;

                    case 3:
                        break;

                    case 4:
                        foreach ($cardIndexes as $cardIndex) {
                            array_push($this->discardPile, $this->cardsOnHand[$userId]['hand'][$cardIndex]);
                            unset($this->cardsOnHand[$userId]['hand'][$cardIndex]);
                        }
                        $this->cardsOnHand[$userId]['hand'] = array_values($this->cardsOnHand[$userId]['hand']);
                        $this->changePlayer();
                        if ($this->checkPopulationCount($this->currentTurn['id']) == 0) {
                            $this->turnSequence = 2;
                        } else {
                            $hybridPlots = $this->getHybridPlotsForId($this->currentTurn['id']);

                            if (count($hybridPlots) > 0) {
                                $this->turnSequence = 1;
                            } else {
                                $this->turnSequence = 2;
                            }
                        }
                        $this->addResources();
                        return true;

                    case 6:
                        foreach ($cardIndexes as $cardIndex) {
                            array_push($this->discardPile, $this->cardsOnHand[$userId]['hand'][$cardIndex]);
                            unset($this->cardsOnHand[$userId]['hand'][$cardIndex]);
                        }
                        $this->cardsOnHand[$userId]['hand'] = array_values($this->cardsOnHand[$userId]['hand']);
                        $this->checkStartingDiscards();
                        return true;
                    default:
                        # code...
                        break;
                }
            default:
                return false;
        }
    }

    public function changePlayer()
    {
        $lastPlayer = end($this->players);
        foreach ($this->players as $player) {
            if ($player['id'] == $this->currentTurn['id']) {
                if ($lastPlayer['id'] == $player['id']) {
                    $this->currentTurn = $this->players[0];
                    $this->currentMessageToLog = $player['name'] . " finished his turn. Current turn is now: " . $this->currentTurn['name'];
                    break;
                } else {
                    $index = array_search($player, $this->players) + 1;
                    $this->currentTurn = $this->players[$index];
                    $this->currentMessageToLog = $player['name'] . " finished his turn. Current turn is now: " . $this->currentTurn['name'];
                    break;
                }
            }
        }
        $this->updateNotes($this->currentTurn['id']);
    }

    public function skipTurnSequence($userId)
    {
        if ($userId != $this->currentTurn['id']) {
            return false;
        }

        switch ($this->turnSequence) {
            case 2:
                $this->turnSequence = 3;
                $this->currentMessageToLog = $this->currentTurn['name'] . " finished the purchasing phase.";
                return true;
            case 3:
                $this->turnSequence = 4;
                $this->currentMessageToLog = $this->currentTurn['name'] . " did not initiate combat.";
                $this->drawCards();
                return true;
            default:
                return false;
        }
    }

    public function drawCards()
    {
        $cardsToDraw = 4;
        for ($i = 0; $i < $cardsToDraw; $i++) {
            array_push($this->cardsOnHand[$this->currentTurn['id']]['hand'], array_pop($this->playDeck));
        }
    }

    public function checkStartingPlots()
    {
        $lastPlayer = end($this->players);
        foreach ($this->players as $player) {
            if ($player['id'] == $this->currentTurn['id']) {
                if ($lastPlayer['id'] == $player['id']) {
                    $this->currentTurn = $this->players[0];
                    break;
                } else {
                    $index = array_search($player, $this->players) + 1;
                    $this->currentTurn = $this->players[$index];
                    break;
                }
            }
        }

        if (empty($this->purchaseablePlots)) {
            $firstKey = array_key_first($this->cardsOnHand);
            if (count($this->cardsOnHand[$firstKey]['plots']) == 3) {
                $this->purchaseablePlots = [
                    array_pop($this->plotDeck),
                    array_pop($this->plotDeck),
                ];
                $this->players = array_reverse($this->players);
                $this->currentTurn = $this->players[0];
                $this->currentMessageToLog = 'Starting plot selected. A new set of plots have been placed. Current turn is now: ' . $this->currentTurn['name'];
                return;
            }

            $this->players = array_reverse($this->players);
            $this->currentTurn = $this->players[0];

            $this->turnSequence = 6;
            array_push(
                $this->purchaseablePlots,
                array_pop($this->plotDeck),
                array_pop($this->plotDeck),
                array_pop($this->plotDeck)
            );

            array_push(
                $this->purchaseableTechs,
                array_pop($this->techDeck),
                array_pop($this->techDeck),
                array_pop($this->techDeck)
            );

            $cardsToDraw = 6;
            foreach ($this->players as $player) {
                for ($i = 0; $i < $cardsToDraw; $i++) {
                    array_push($this->cardsOnHand[$player['id']]['hand'], array_pop($this->playDeck));
                }
                $cardsToDraw++;
            }

            $this->currentMessageToLog = 'All starting plots selected. 2 Cards must now be discarded by each player';
        } else {
            $this->currentMessageToLog = 'Starting plot selected. Current turn is now: ' . $this->currentTurn['name'];
        }
    }

    public function checkStartingDiscards()
    {
        $lastPlayer = end($this->players);
        foreach ($this->players as $player) {
            if ($player['id'] == $this->currentTurn['id']) {
                if ($lastPlayer['id'] == $player['id']) {
                    $this->currentTurn = $this->players[0];
                    break;
                } else {
                    $index = array_search($player, $this->players) + 1;
                    $this->currentTurn = $this->players[$index];
                    break;
                }
            }
        }

        $discardedCardsNeeded = count($this->players) * 2;

        if (count($this->discardPile) == $discardedCardsNeeded) {
            $this->addResources();
            $this->turnSequence = 2;
            $this->updateNotes($this->currentTurn['id']);

            $this->currentMessageToLog = 'Cards discarded. Game can begin';
        } else {
            $this->currentMessageToLog = '2 cards have been discarded. Current turn is now: ' . $this->currentTurn['name'];
        }
    }

    public function addResources()
    {
        $currentId = $this->currentTurn['id'];

        foreach ($this->cardsOnHand[$currentId]['plots'] as $plot) {
            switch ($plot['specialEffectId']) {
                case 1:
                    $currentCommerce = $this->cardsOnHand[$currentId]['resources']['commerce'];
                    $newCommerce = $currentCommerce + 2 + $plot['attachedPopulation'];
                    $this->cardsOnHand[$currentId]['resources']['commerce'] = $newCommerce;
                    break;

                case 2:
                    $currentProduction = $this->cardsOnHand[$currentId]['resources']['production'];
                    $newProduction = $currentProduction + 2 + $plot['attachedPopulation'];
                    $this->cardsOnHand[$currentId]['resources']['production'] = $newProduction;
                    break;

                case 3:
                    $currentFood = $this->cardsOnHand[$currentId]['resources']['food'];
                    $newFood = $currentFood + 2 + $plot['attachedPopulation'];
                    $this->cardsOnHand[$currentId]['resources']['food'] = $newFood;
                    break;

                case 4:
                    $currentProduction = $this->cardsOnHand[$currentId]['resources']['production'];
                    $newProduction = $currentProduction + 1;
                    $this->cardsOnHand[$currentId]['resources']['production'] = $newProduction;

                    $currentCommerce = $this->cardsOnHand[$currentId]['resources']['commerce'];
                    $newCommerce = $currentCommerce + 1;
                    $this->cardsOnHand[$currentId]['resources']['commerce'] = $newCommerce;
                    break;

                case 5:
                    $currentFood = $this->cardsOnHand[$currentId]['resources']['food'];
                    $newFood = $currentFood + 1;
                    $this->cardsOnHand[$currentId]['resources']['food'] = $newFood;

                    $currentProduction = $this->cardsOnHand[$currentId]['resources']['production'];
                    $newProduction = $currentProduction + 1;
                    $this->cardsOnHand[$currentId]['resources']['production'] = $newProduction;
                    break;

                case 6:
                    $currentFood = $this->cardsOnHand[$currentId]['resources']['food'];
                    $newFood = $currentFood + 1;
                    $this->cardsOnHand[$currentId]['resources']['food'] = $newFood;

                    $currentCommerce = $this->cardsOnHand[$currentId]['resources']['commerce'];
                    $newCommerce = $currentCommerce + 1;
                    $this->cardsOnHand[$currentId]['resources']['commerce'] = $newCommerce;
                    break;

                default:
                    # code...
                    break;
            }
        }
    }

    public function updateNotes($playerId)
    {
        $this->playerNotes[$playerId] = array();
        if (!$this->cardsOnHand[$playerId]['freePopUsed']) {
            array_push($this->playerNotes[$playerId], "One free population available for any plot.");
        }
    }

    public function purchaseTech($playerId, $cardIndex)
    {
        $commerceAvailable = $this->cardsOnHand[$playerId]['resources']['commerce'];
        $cost = $this->purchaseableTechs[$cardIndex]['cost'];
        $modifier = 0;

        if ($commerceAvailable >= ($cost + $modifier)) {
            $card = array_splice($this->purchaseableTechs, $cardIndex, 1);
            array_push($this->cardsOnHand[$playerId]['techs'], $card[0]);
            array_push(
                $this->purchaseableTechs,
                array_pop($this->techDeck),
            );
        }
    }

    public function purchaseBuilding($playerId, $cardIndex, $plotId)
    {
        $production = $this->cardsOnHand[$playerId]['resources']['production'];
        $cost = $this->cardsOnHand[$playerId]['hand'][$cardIndex]['cost'];
        $modifier = 0;

        if ($production < $cost + $modifier) {
            return false;
        }

        $population = $this->cardsOnHand[$playerId]['plots'][$plotId]['attachedPopulation'];
        $buildings = count($this->cardsOnHand[$playerId]['plots'][$plotId]['attachedBuildings']);

        $allowed = false;
        if ($population >= 1 && $buildings == 0) {
            $allowed = true;
        } else if ($population >= 3 && $buildings == 1) {
            $allowed = true;
        }

        if ($allowed) {
            array_push($this->cardsOnHand[$playerId]['plots'][$plotId]['attachedBuildings'], $this->cardsOnHand[$playerId]['hand'][$cardIndex]);
            unset($this->cardsOnHand[$playerId]['hand'][$cardIndex]);
            $this->cardsOnHand[$playerId]['hand'] = array_values($this->cardsOnHand[$playerId]['hand']);
            return true;
        }

        return false;
    }

    public function purchasePlot($playerId, $cardIndex, $resourceDistribution)
    {
        if ($playerId != $this->currentTurn['id']) {
            return false;
        }

        $commerce = $resourceDistribution['commerce'];
        $food = $resourceDistribution['food'];
        $production = $resourceDistribution['production'];

        $diffCommerce = $this->cardsOnHand[$playerId]['resources']['commerce'] - $commerce;
        $diffFood = $this->cardsOnHand[$playerId]['resources']['food'] - $food;
        $diffProduction = $this->cardsOnHand[$playerId]['resources']['production'] - $production;

        $sum = $diffCommerce + $diffFood + $diffProduction;
        $needed = $this->getNeededResourcesForPlotForUser($playerId);
        if ($sum != $needed) {
            return false;
        }


        $this->cardsOnHand[$playerId]['resources']['commerce'] = $commerce;
        $this->cardsOnHand[$playerId]['resources']['food'] = $food;
        $this->cardsOnHand[$playerId]['resources']['production'] = $production;

        $card = array_splice($this->purchaseablePlots, $cardIndex, 1);
        array_push($this->cardsOnHand[$playerId]['plots'], $card[0]);
        array_push(
            $this->purchaseablePlots,
            array_pop($this->plotDeck),
        );
        $this->currentMessageToLog = $this->currentTurn['name'] . " has purchased a plot";
        return true;
    }

    public function getNeededResourcesForPlotForUser($playerId)
    {
        $plots = count($this->cardsOnHand[$playerId]['plots']);
        return $plots * 5 - 5;
    }

    public function checkPopulationCount($playerId)
    {
        $populationCount = 0;
        foreach ($this->cardsOnHand[$playerId]['plots'] as $plot) {
            $populationCount += $plot['attachedPopulation'];
        }
        return $populationCount;
    }

    public function checkCanPurchasePopulation($playerId, $cardIndex)
    {
        if (!$this->cardsOnHand[$playerId]['freePopUsed']) {
            $this->cardsOnHand[$playerId]['freePopUsed'] = true;
            $this->updateNotes($playerId);
            return true;
        }

        $food = $this->cardsOnHand[$playerId]['resources']['food'];

        $base = 6;
        $price = $this->cardsOnHand[$playerId]['plots'][$cardIndex]['attachedPopulation'] * 2 + $base;

        return $food >= $price;
    }

    public function getHybridPlotsForId($playerId)
    {
        $plots = $this->cardsOnHand[$playerId]['plots'];

        $hybridPlots = array();
        foreach ($plots as $plot) {
            if ($plot['attachedPopulation'] == 0) {
                continue;
            }
            if (
                $plot['specialEffectId'] == 4 ||
                $plot['specialEffectId'] == 5 ||
                $plot['specialEffectId'] == 6
            ) {
                array_push($hybridPlots, $plot);
            }
        }

        return $hybridPlots;
    }

    public function addResourceDistribution($playerId, $resources)
    {
        if ($playerId != $this->currentTurn['id']) {
            return false;
        }

        $food = $resources['food'];
        $commerce = $resources['commerce'];
        $production = $resources['production'];

        $hybridPlots = $this->getHybridPlotsForId($playerId);
        $populations = 0;
        foreach ($hybridPlots as $hybridPlots) {
            $populations += $hybridPlots['attachedPopulation'];
        }

        $sum = $food + $commerce + $production;
        if ($sum != $populations || $populations == 0) {
            return false;
        }

        $this->cardsOnHand[$playerId]['resources']['food'] += $food;
        $this->cardsOnHand[$playerId]['resources']['commerce'] += $commerce;
        $this->cardsOnHand[$playerId]['resources']['production'] += $production;

        $this->turnSequence = 2;
        $this->currentMessageToLog = $this->currentTurn['name'] . " has distributed all resources";
        return true;
    }

    public function getPlotsOpenForBuildingsForUser($playerId)
    {
        $plots = $this->cardsOnHand[$playerId]['plots'];
        $allowedPlots = array();

        foreach ($plots as $plotKey => $plotValue) {
            $population = $plotValue['attachedPopulation'];
            $buildings = $plotValue['attachedBuildings'];
            if ($population >= 1 && count($buildings) == 0) {
                $allowedPlots[$plotKey] = $plotValue;
                continue;
            } else if ($population >= 3 && count($buildings) == 1) {
                $allowedPlots[$plotKey] = $plotValue;
            }
        }

        return $allowedPlots;
    }

    public function updateSpecialResources() {
        foreach ($this->cardsOnHand as $playerId => $cards) {
            $happiness = 0;
            $culture = 0;
            $victoryPoints = 0;

            foreach ($cards['plots'] as $plot) {
                $victoryPoints += $plot['attachedPopulation'];

                foreach ($plot['attachedBuildings'] as $building) {
                    if ($building['type'] == 'Wonder') {
                        $victoryPoints++;
                    }
                }
            }

            foreach ($cards['techs'] as $tech) {
                # code...
            }

            $victoryPoints += count($cards['techs']);

            $this->cardsOnHand[$playerId]['resources']['victoryPoints'] = $victoryPoints;
            $this->cardsOnHand[$playerId]['resources']['happiness'] = $happiness;
            $this->cardsOnHand[$playerId]['resources']['culture'] = $culture;
        }
    }

    public function checkVictory() {
        if (empty($this->techDeck)) {
            $playerVictoryPoints = array();
            foreach ($this->cardsOnHand as $playerId => $cards) {
                $playerVictoryPoints[$playerId] = $cards['resources']['victoryPoints'];
            }

            arsort($playerVictoryPoints);

            $winnerKey = key($playerVictoryPoints[0]);
            foreach ($this->players as $player) {
                if ($winnerKey == $player['id']) {
                    $this->winner = $player;
                    break;
                }
            }

            $this->currentTurn = -1;
            $this->turnSequence = -1;

            return true;
        }

        return false;
    }

    public function newState()
    {
        $plotId = DB::table('card_types')->where('name', 'Plot')->value('id');
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
                        $json->specialEffect,
                        $json->specialEffectId,
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
        return isset($this->currentTurn) ? $this->currentTurn : null;
    }

    public function getTurnSequence()
    {
        return isset($this->turnSequence) ? $this->turnSequence : 0;
    }

    public function getCardsInHand()
    {
        return $this->cardsOnHand;
    }

    public function getCardsInHandForUser($id)
    {
        return $this->cardsOnHand[$id];
    }

    public function getPlayDeck()
    {
        return $this->playDeck;
    }

    public function getDiscardPile()
    {
        return $this->discardPile;
    }

    public function getPurchaseablePlots()
    {
        return $this->purchaseablePlots;
    }

    public function getPurchaseableTechs()
    {
        return $this->purchaseableTechs;
    }

    public function getAttacking()
    {
        return $this->attacking;
    }

    public function getDefending()
    {
        return $this->defending;
    }

    public function getCurrentMessageToLog()
    {
        return $this->currentMessageToLog;
    }

    public function getPlayerNotes()
    {
        return $this->playerNotes;
    }
}
