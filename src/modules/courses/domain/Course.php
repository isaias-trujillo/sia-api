<?php

namespace courses\domain;

use modules\shared\domain\Entity;

final class Course extends Entity
{
    private $name;
    private $code;

    public function __construct(string $name, $code = null)
    {
        $this->name = $name;
        $this->code = $code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function code()
    {
        return $this->code;
    }

    function to_array(): array
    {
        return [
            'name' => $this->name(),
            'code' => $this->code()
        ];
    }
}