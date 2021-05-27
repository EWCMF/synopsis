<?php

namespace App\Classes;

use JsonSerializable;

class BuildingCard implements JsonSerializable {
    private string $name;
    private int $cost;
    private string $specialEffect;
    private int $specialEffectId;

    public function __construct($name, $cost, $specialEffect, $specialEffectId) {
        $this->name = $name;
        $this->cost = $cost;
        $this->specialEffect = $specialEffect;
        $this->specialEffectId = $specialEffectId;
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

    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}
