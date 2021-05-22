<?php

namespace App\Classes;

class BonusResourceCard {
    private string $resource;
    private int $count;
    private bool $isWild;

    public function __construct($resource, $count, $isWild) {
        $this->$resource = $resource;
        $this->$count = $count;
        $this->$isWild = $isWild;
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
}
