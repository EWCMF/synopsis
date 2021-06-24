<?php

namespace App\Classes;

use JsonSerializable;

Class ComputerPlayer implements JsonSerializable
{
    private $foeHand = array();
    private $ownHand = array();
    private int $aggressivenessScore;
    private int $hoarderScore;
    private $id = 'CPU';

    public function __construct($aggressivenessScore, $hoarderScore, $json = null)
    {
        $this->aggressivenessScore = $aggressivenessScore;
        $this->hoarderScore = $hoarderScore;
        if ($json != null) {
            $this->set(json_decode($json, true));
        }
    }

    public function setFoeHand($foeHand) {
        $this->foeHand = $foeHand;
    }

    public function setOwnHand($ownHand) {
        $this->ownHand = $ownHand;
    }

    public function evaluateMove(State $state) {
        $turnSequence = $state->getTurnSequence();

        switch ($turnSequence) {
            case 1:
                return $this->evaluateResourceDistribution($state);

            case 2:
                return $this->evaluatePurchasingSequence($state);

            case 3:
                return $this->evaluateAttack($state);

            case 4:
                return $this->evaluateDrawAndDiscard($state);

            case 5:
                return $this->evaluateStartingPlots($state);

            case 6:
                return $this->evaluateStartingDiscards($state);

            case 7:
                return $this->evaluateDefense($state);

            default:
                return false;
        }

    }


    public function evaluateStartingPlots(State $state) {
        $ownedPlots = $this->ownHand['plots'];

        $purchaseablePlots = $state->getPurchaseablePlots();

        $decisionWeighting = array();
        foreach ($purchaseablePlots as $index => $plot) {
            $decisionWeighting[$index] = 50;

            foreach ($ownedPlots as $ownedPlot) {
                if ($ownedPlot['specialEffectId'] == $plot['specialEffectId']) {
                    $decisionWeighting[$index] -= 25;
                }
            }
        }

        $selectedIndex = $this->randomSelectWeighted($decisionWeighting);

        $state->pickCard($selectedIndex, 'purchaseablePlots', 'CPU');
    }

    public function evaluateStartingDiscards(State $state) {
        $cardsInHand = $this->ownHand['hand'];

        $cardsToDiscard = 2;
        $selectedIndexes = array();
        for ($i = 0; $i < $cardsToDiscard; $i++) {
            $decisionWeighting = array();
            foreach ($cardsInHand as $index => $card) {
                $decisionWeighting[$index] = 50;

                if ($card['type'] == 'Wonder') {
                    $decisionWeighting[$index] -= 40;
                }

                foreach ($cardsInHand as $compareIndex => $compareCard) {
                    if ($compareIndex == $index) {
                        continue;
                    }

                    if ($card['type'] == $compareCard['type'] && $card['specialEffectId'] == $compareCard['specialEffectId']) {
                        $decisionWeighting[$index] += 25;
                    }
                }
            }

            $selectedIndex = $this->randomSelectWeighted($decisionWeighting);
            array_push($selectedIndexes, $selectedIndex);
            unset($cardsInHand[$selectedIndex]);
        }

        return $state->pickCards($selectedIndexes, 'playDeck', 'CPU');
    }

    public function evaluateResourceDistribution(State $state) {
        $hybridPlots = $state->getHybridPlotsForId($this->id);
        $resourceDistribution = [
            'commerce' => 0,
            'food' => 0,
            'production' => 0,
        ];

        foreach ($hybridPlots as $hybridPlot) {
            $populationCount = $hybridPlot['attachedPopulation'];
            switch ($hybridPlot['specialEffectId']) {
                case 4:
                    $chance = rand(0, 1);
                    if ($chance == 1) {
                        $resourceDistribution['production'] += $populationCount;
                    } else {
                        $resourceDistribution['commerce'] += $populationCount;
                    }
                    break;

                case 5:
                    $chance = rand(0, 1);
                    if ($chance == 1) {
                        $resourceDistribution['production'] += $populationCount;
                    } else {
                        $resourceDistribution['food'] += $populationCount;
                    }
                    break;

                case 6:
                    $chance = rand(0, 1);
                    if ($chance == 1) {
                        $resourceDistribution['commerce'] += $populationCount;
                    } else {
                        $resourceDistribution['food'] += $populationCount;
                    }
                    break;

                default:
                    # code...
                    break;
            }
        }

        return $state->addResourceDistribution($this->id, $resourceDistribution);
    }


    public function evaluatePurchasingSequence(State $state) {
        $cardsInHand = $this->ownHand['hand'];

        if ($this->ownHand['freePopUsed'] == false) {
            $freePopIndex = array_rand($this->ownHand['plots'], 1);
            return $state->purchasePopulation($this->id, $freePopIndex);
        }

        foreach ($cardsInHand as $cardIndex => $card) {
            if ($card['type'] == 'Bonus resource') {
                $bonusResourceOption = 0;
                if ($card['specialEffectId'] == 7 || $card['specialEffectId'] == 8) {
                    $bonusResourceOption = rand(1, 3);
                }
                return $state->pickCard($cardIndex, 'bonusResource', $this->id, $bonusResourceOption);
            }
        }


        $commerce = $this->ownHand['resources']['commerce'];
        $food = $this->ownHand['resources']['food'];
        $production = $this->ownHand['resources']['production'];
        $sum = $commerce + $food + $production;

        $purchaseableTechs = $state->getPurchaseableTechs();
        $affordableTechs = array();

        foreach ($purchaseableTechs as $techIndex => $tech) {
            if ($tech['cost'] <= $commerce) {
                $affordableTechs[$techIndex] = $tech;
            }
        }

        if (!empty($affordableTechs)) {
            $techDecisionWeighting = array();
            foreach ($affordableTechs as $techIndex => $tech) {
                $techDecisionWeighting[$techIndex] = 50;

                if ($tech['specialEffectId'] == 15) {
                    $techDecisionWeighting[$techIndex] += 50;
                }
            }

            $selectedTech = $this->randomSelectWeighted($techDecisionWeighting);
            return $state->pickCard($selectedTech, 'purchaseableTech', $this->id);
        }

        $buildings = array();
        foreach ($cardsInHand as $cardIndex => $card) {
            if ($card['type'] == 'Building' || $card['type'] == 'Wonder') {
                $buildings[$cardIndex] = $card;
            }
        }

        $affordableBuildings = array();
        foreach ($buildings as $buildingIndex => $building) {
            if ($building['cost'] <= $production) {
                $affordableBuildings[$buildingIndex] = $building;
            }
        }

        $skipPurchaseBuilding = false;
        $allowedPlots = $state->getPlotsOpenForBuildingsForUser($this->id);
        if ($this->hoarderScore > 0) {
            $chance = rand(0, 100);
            if ($chance > 20) {
                $skipPurchaseBuilding = true;
            }
        }

        if (empty($allowedPlots)) {
            $skipPurchaseBuilding = true;
        }

        if (empty($affordableBuildings)) {
            $skipPurchaseBuilding = true;
        }

        if (!$skipPurchaseBuilding) {
            $buildingDecisionWeighting = array();
            foreach ($affordableBuildings as $buildingIndex => $building) {
                $buildingDecisionWeighting[$buildingIndex] = 50;

                if ($building['type'] == 'Wonder') {
                    $buildingDecisionWeighting[$buildingIndex] += 50;
                }
            }

            $selectedPlotForBuilding = array_rand($allowedPlots, 1);
            $selectedBuilding = $this->randomSelectWeighted($buildingDecisionWeighting);
            return $state->pickCard($selectedBuilding, 'building', $this->id, $selectedPlotForBuilding);
        }

        $skipPurchasePopulation = false;
        $plotsWithAffordablePopulation = $state->getPlotsWithAffordablePopulationForId($this->id);
        if ($this->hoarderScore > 0) {
            $chance = rand(0, 100);
            if ($chance > 20) {
                $skipPurchasePopulation = true;
            }
        }

        if (empty($plotsWithAffordablePopulation)) {
            $skipPurchasePopulation = true;
        }

        if (!$skipPurchasePopulation) {
            $selectedPlotForPop = array_rand($plotsWithAffordablePopulation, 1);
            return $state->pickCard($selectedPlotForPop, 'ownPlots', $this->id);
        }

        $purchaseablePlots = $state->getPurchaseablePlots();
        $needed = $state->getNeededResourcesForPlotForUser($this->id);
        if ($sum >= $needed) {
                $resourceDistribution = [
                    'commerce' => 0,
                    'food' => 0,
                    'production' => 0,
                ];

                for ($i = 0; $i < $needed; $i++) {
                    if ($commerce > 0) {
                        $commerce--;
                        $resourceDistribution['commerce']++;
                        if (array_sum($resourceDistribution) == $needed) {
                            break;
                        }
                    }

                    if ($food > 0) {
                        $food--;
                        $resourceDistribution['food']++;
                        if (array_sum($resourceDistribution) == $needed) {
                            break;
                        }
                    }

                    if ($production > 0) {
                        $production--;
                        $resourceDistribution['production']++;
                        if (array_sum($resourceDistribution) == $needed) {
                            break;
                        }
                    }
                }

                $plotIndex = array_rand($purchaseablePlots, 1);

                $resourcesRemaining = [
                    'commerce' => $commerce,
                    'food' => $food,
                    'production' => $production,
                ];

                return $state->purchasePlot($this->id, $plotIndex, $resourcesRemaining);
        }

        return $state->skipTurnSequence($this->id);
    }

    public function evaluateDefense(State $state) {
        if ($this->aggressivenessScore == 1) {
            $this->aggressivenessScore++;
        }



        $cardsInHand = $this->ownHand['hand'];
        $numberOfAttackers = count($state->getAttacking()['units']);
        $defenderIndexes = array();
        foreach ($cardsInHand as $cardIndex => $card) {
            if ($card['type'] == 'Unit') {
                array_push($defenderIndexes, $cardIndex);
            }

            if ($numberOfAttackers == count($defenderIndexes)) {
                break;
            }
        }

        if (empty($defenderIndexes)) {
            return $state->skipTurnSequence($this->id);
        }

        return $state->pickCards($defenderIndexes, 'playDeck', $this->id);
    }

    public function evaluateDrawAndDiscard(State $state) {
        $cardsInHand = $this->ownHand['hand'];
        $selectedIndexes = array_rand($cardsInHand, 2);

        return $state->pickCards($selectedIndexes, 'playDeck', $this->id);
    }

    public function evaluateAttack(State $state) {
        $cardsInHand = $this->ownHand['hand'];
        $units = array();

        foreach ($cardsInHand as $cardIndex => $card) {
            if ($card['type'] == 'Unit') {
                $units[$cardIndex] = $card;
            }
        }

        if ($this->aggressivenessScore == 1 || empty($units)) {
            return $state->skipTurnSequence($this->id);
        } else {
            if ($this->aggressivenessScore == 2) {
                $chance = rand(0, 1);
                if ($chance == 0) {
                    return $state->skipTurnSequence($this->id);
                }
            }

            $numberToAttackWith = rand(1, count($units));
            if ($numberToAttackWith == 1) {
                $value = array_rand($units, 1);
                $selectedAttackerIndexes[$value] = $value;
            } else {
                $selectedAttackerIndexes = array_rand($units, $numberToAttackWith);
            }

            return $state->pickCards($selectedAttackerIndexes, 'playDeck', $this->id);
        }

    }

    public function randomSelectWeighted($decisionWeighting) {
        asort($decisionWeighting);

        $total = array();
        $cumulativeSum = 0;

        foreach ($decisionWeighting as $key => $number) {
            $cumulativeSum += $number;
            $total[$key] = $cumulativeSum;
        }

        $sum = array_sum($decisionWeighting);
        $pick = rand(0, $sum);

        $selectedIndex = 0;
        foreach ($total as $key => $value) {
            if ($pick <= $value) {
                $selectedIndex = $key;
            }
        }

        return $selectedIndex;
    }


    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }

    public function set($data)
    {
        foreach ($data as $key => $value) $this->{$key} = $value;
    }
}
