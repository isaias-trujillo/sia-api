<?php

namespace modules\attendances\application;

use modules\attendances\domain\Repository;

class GetAttendancesOfGroup
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $classroom, $turn, string $course): array
    {
       return $this->repository->get_attendances_of_group($classroom, $turn, $course);
    }
}