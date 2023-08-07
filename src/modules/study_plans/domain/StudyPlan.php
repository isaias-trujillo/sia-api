<?php

namespace modules\study_plans\domain;

use modules\shared\domain\Entity;

final class StudyPlan extends Entity
{
    private $name;
    private $year;
    private $career_id;

    public function __construct(string $name, int $year, int $career_id)
    {
        $this->name = $name;
        $this->year = $year;
        $this->career_id = $career_id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function career_id(): int
    {
        return $this->career_id;
    }

    function to_array(): array
    {
        return [
            'name' => $this->name(),
            'year' => $this->year(),
            'career id' => $this->career_id
        ];
    }

    public function __toString()
    {
        return "Plan de estudios $this->name de la E.P. ($this->career_id).";
    }
}