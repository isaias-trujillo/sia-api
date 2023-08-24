<?php

namespace modules\attendances\application;

use modules\attendances\domain\Repository;

class RegisterAttendancesOfGroup
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $teacher_id, string $classroom, $turn, string $course, array $attended_by_student_id): array
    {
       return $this->repository->register_attendances_of_group($teacher_id, $classroom, $turn, $course, $attended_by_student_id);
    }
}