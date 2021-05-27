<?php

namespace App\Classes;

use JsonSerializable;

class TechnologyCard implements JsonSerializable {
    private string $name;
    private int $cost;
    private string $specialEffect;
    private int $specialEffectId;
    private int $maxCardsInDeck;

    public function __construct($name, $cost, $specialEffect, $specialEffectId, $maxCardsInDeck) {
        $this->name = $name;
        $this->cost = $cost;
        $this->specialEffect = $specialEffect;
        $this->specialEffectId = $specialEffectId;
        $this->maxCardsInDeck = $maxCardsInDeck;
    }

    public function getName() {
        return $this->name;
    }

    public function getCost() {
        return $this->cost;
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
