<?php

namespace App\Classes;

class BuildingCard {
    private int $cost;
    private string $specialEffect;
    private int $specialEffectId;

    public function __construct($cost, $specialEffect, $specialEffectId) {
        $this->$cost = $cost;
        $this->$specialEffect = $specialEffect;
        $this->$specialEffectId = $specialEffectId;
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
}
