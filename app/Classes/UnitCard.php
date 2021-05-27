<?php

namespace App\Classes;

use JsonSerializable;

class UnitCard implements JsonSerializable {
    private string $name;
    private string $specialEffect;
    private int $specialEffectId;

    public function __construct($name, $specialEffect, $specialEffectId) {
        $this->name = $name;
        $this->specialEffect = $specialEffect;
        $this->specialEffectId = $specialEffectId;
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

    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}
