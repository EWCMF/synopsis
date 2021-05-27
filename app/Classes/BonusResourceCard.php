<?php

namespace App\Classes;

use JsonSerializable;

class BonusResourceCard implements JsonSerializable {
    private string $name;
    private string $resource;
    private int $count;
    private bool $isWild;

    public function __construct($name, $resource, $count, $isWild) {
        $this->name = $name;
        $this->resource = $resource;
        $this->count = $count;
        $this->isWild = $isWild;
    }

    public function getName() {
        return $this->name;
    }

    public function getResource() {
        return $this->resource;
    }

    public function getCount() {
        return $this->count;
    }

    public function isWild() {
        return $this->isWild;
    }

    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}
