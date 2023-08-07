<?php

namespace modules\careers\domain;

use modules\shared\domain\Entity;

class Career extends Entity
{
    private $name;
    private $abbreviation;
    private $order;

    public function __construct(string $name, string $abbreviation, int $order)
    {
        $this->name = $name;
        $this->abbreviation = $abbreviation;
        $this->order = $order;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function abbreviation(): string
    {
        return $this->abbreviation;
    }

    public function order(): int
    {
        return $this->order;
    }

    function to_array(): array
    {
        return [
          'name' => $this->name(),
          'abbreviation' => $this->abbreviation(),
          'order' => $this->order(),
        ];
    }
    public function __toString()
    {
        $order = $this->order();
        return "Escuela Profesional ($order)";
    }
}