<?php

namespace modules\courses\domain;

use modules\shared\domain\Entity;

final class Course extends Entity
{
    private $study_plan_id;
    private $cycle;
    private $code;
    private $name;
    private $credits;

    public function __construct(int $study_plan_id, int $cycle, string $code, string $name, int $credits)
    {
        $this->study_plan_id = $study_plan_id;
        $this->cycle = $cycle;
        $this->code = $code;
        $this->name = $name;
        $this->credits = $credits;
    }

    public function study_plan_id(): int
    {
        return $this->study_plan_id;
    }

    public function cycle(): int
    {
        return $this->cycle;
    }

    public function code(): string
    {
        return $this->code;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function credits(): int
    {
        return $this->credits;
    }

    function to_array(): array
    {
        return [
            'study plan id' => $this->study_plan_id(),
            'cycle' => $this->cycle(),
            'code' => $this->code(),
            'name' => $this->name(),
            'credits' => $this->credits(),
        ];
    }

    public function __toString()
    {
        $result = "";
        $data = $this->to_array();
        $index = 0;
        $limit = count($data) - 1;
        foreach ($data as $key => $value) {
            $result = $result."'$key' : '$value'";
            if ($index < $limit) {
                $result = $result . ", ";
            }
        }
        return "[$result]";
    }
}