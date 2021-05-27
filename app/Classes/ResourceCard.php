<?php

namespace App\Classes;

use JsonSerializable;

class ResourceCard implements JsonSerializable {
    private string $name;
    private string $resource;
    private int $count;

    public function __construct($name, $resource, $count) {
        $this->name = $name;
        $this->resource = $resource;
        $this->count = $count;
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

    public function jsonSerialize() {
        $vars = get_object_vars($this);

        return $vars;
    }
}
