<?php

namespace App\Classes;

use JsonSerializable;

class UnitCard implements JsonSerializable {
    private string $name;
    private string $type = 'Unit';
    private string $specialEffect;
    private int $specialEffectId;
    private int $maxCardsInDeck;

    public function __construct($name, $specialEffect, $specialEffectId, $maxCardsInDeck) {
        $this->name = $name;
        $this->specialEffect = $specialEffect;
        $this->specialEffectId = $specialEffectId;
        $this->maxCardsInDeck = $maxCardsInDeck;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getSpecialEffect() {
        return $this->specialEffect;
    }

    public function getSpecialEffectId() {
        return $this->specialEffectId;
    }

    public function getMaxCardsInDeck() {
        return $this->maxCardsInDeck;
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}
