<?php

namespace App\Classes;

use JsonSerializable;

class PlotCard implements JsonSerializable {
    private string $name;
    private string $type = 'plot';
    private string $specialEffect;
    private int $specialEffectId;
    private int $maxCardsInDeck;
    private int $attachedPopulation = 0;
    private $attachedBuildings = array();

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

    public function addPopulation(int $number) {
        $this->attachedPopulation += $number;
    }

    public function subtractPopulation(int $number) {
        $this->attachedPopulation -= $number;
    }

    public function addBuildings(...$cards) {
        array_push($this->attachedBuildings, $cards);
    }

    public function subtractBuildings(...$cards) {
        foreach ($cards as $card) {
            unset($this->attachedBuildings, $card);
        }
    }

    public function getAttachedPopulation() {
        return $this->attachedPopulation;
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}
