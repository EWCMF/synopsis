<?php

namespace App\Classes;

use App\Models\Card;

class State {
    private $players = array();
    private $activePlayers = array();

    private $playDeck = array();
    private $techDeck = array();
    private $plotDeck = array();
    private $discardPile = array();

    private $cardsOnHand = array();
    private int $currentTurn;
    private int $turnSequence;

    public function __construct() {

        Card::find();
    }
}
