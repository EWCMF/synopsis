<?php

namespace App\Classes;

use App\Models\Card;
use Illuminate\Support\Facades\DB;

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
        $plotId = DB::table('card_types')->where('name', 'Plot')->value('id');
        $resourceId = DB::table('card_types')->where('name', 'Resource')->value('id');
        $populationId = DB::table('card_types')->where('name', 'Population')->value('id');
        $technologyId = DB::table('card_types')->where('name', 'Technology')->value('id');
        $unitId = DB::table('card_types')->where('name', 'Unit')->value('id');
        $buildingId = DB::table('card_types')->where('name', 'Building')->value('id');
        $wonderId = DB::table('card_types')->where('name', 'Wonder')->value('id');
        $bonusId = DB::table('card_types')->where('name', 'Bonus resource')->value('id');

        $techJson = Card::where('card_type_id', $technologyId)->get();
        dd($techJson);
    }
}
