<?php

namespace App\Classes;

Class ComputerPlayer
{
    private int $numberOfOpponentCards;
    private $ownHand = array();
    private int $aggressivenessScore;
    private int $turtleScore;
    private int $turnsSinceLastAttack;

    public function __construct($aggressivenessScore, $turtleScore)
    {
        $this->aggressivenessScore = $aggressivenessScore;
        $this->turtleScore = $turtleScore;
    }

    public function setNumberOfOpponentCards($numberOfOpponentCards) {
        $this->numberOfOpponentCards = $numberOfOpponentCards;
    }

    public function setOwnHand($ownHand) {
        $this->ownHand = $ownHand;
    }

    public function evaluateMove(State $state) {
        
    }
}
