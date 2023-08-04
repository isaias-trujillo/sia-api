<?php

namespace modules\teachers\domain;

use modules\shared\domain\Entity;

final class Teacher extends Entity
{
    private $paternal_surname;
    private $maternal_surname;
    private $firstname;
    private $dni;

    public function __construct(string $paternal_surname, string $maternal_surname, string $firstname, string $dni)
    {
        $this->paternal_surname = $paternal_surname;
        $this->maternal_surname = $maternal_surname;
        $this->firstname = $firstname;
        $this->dni = $dni;
    }

    public function paternal_surname(): string
    {
        return $this->paternal_surname;
    }

    public function maternal_surname(): string
    {
        return $this->maternal_surname;
    }

    public function firstname(): string
    {
        return $this->firstname;
    }

    public function dni(): string
    {
        return $this->dni;
    }

    public function to_array(): array
    {
        return [
            'paternal_surname' => $this->paternal_surname(),
            'maternal_surname' => $this->maternal_surname(),
            'firstname' => $this->firstname(),
            'dni' => $this->dni(),
        ];
    }
}