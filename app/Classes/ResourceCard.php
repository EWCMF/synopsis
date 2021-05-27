<?php

namespace App\Classes;

class ResourceCard {
    private string $resource;
    private int $count;

    public function __construct($name, $resource, $count) {
        $this->$name = $name;
        $this->$resource = $resource;
        $this->$count = $count;
    }

    public function getResource() {
        return $this->resource;
    }

    public function getCount() {
        return $this->count;
    }
}
