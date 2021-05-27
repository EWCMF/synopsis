<?php

namespace App\Classes;

class PopulationCard {
    private string $specialEffect;
    private int $specialEffectId;

    public function __construct($name, $specialEffect, $specialEffectId) {
        $this->$name = $name;
        $this->$specialEffect = $specialEffect;
        $this->$specialEffectId = $specialEffectId;
    }

    public function getName() {
        return $this->name;
    }

    public function getSpecialEffect() {
        return $this->specialEffect;
    }

    public function getSpecialEffectId() {
        return $this->specialEffectId;
    }
}
