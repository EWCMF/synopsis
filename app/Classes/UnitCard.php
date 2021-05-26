<?php

namespace App\Classes;

class UnitCard {
    private string $specialEffect;
    private int $specialEffectId;

    public function __construct($specialEffect, $specialEffectId) {
        $this->$specialEffect = $specialEffect;
        $this->$specialEffectId = $specialEffectId;
    }

    public function getSpecialEffect() {
        return $this->specialEffect;
    }

    public function getSpecialEffectId() {
        return $this->specialEffectId;
    }
}
