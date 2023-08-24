<?php

namespace modules\attendances\application;

use modules\attendances\domain\Repository;

class GetAttendancesOfTeacher
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $teacher_id, string $classroom, $turn, string $course): array
    {
       return $this->repository->get_attendances_of_teacher($teacher_id, $classroom, $turn, $course);
    }
}