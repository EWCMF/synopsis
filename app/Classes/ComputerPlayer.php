<?php

namespace App\Classes;

use JsonSerializable;

Class ComputerPlayer implements JsonSerializable
{
    private $foeHand = array();
    private $ownHand = array();
    private int $aggressivenessScore;
    private int $turtleScore;
    private int $turnsSinceLastAttack;

    public function __construct($aggressivenessScore, $turtleScore, $json = null)
    {
        $this->aggressivenessScore = $aggressivenessScore;
        $this->turtleScore = $turtleScore;
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
                # code...
                break;

            case 2:
                # code...
                break;

            case 3:
                # code...
                break;

            case 4:
                # code...
                break;

            case 5:
                return $this->evaluateStartingPlots($state);

            case 6:
                # code...
                break;

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
