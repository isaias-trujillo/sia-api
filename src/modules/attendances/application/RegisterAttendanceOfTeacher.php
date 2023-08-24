<?php

namespace modules\attendances\application;

use modules\attendances\domain\Repository;

class RegisterAttendanceOfTeacher
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $teacher_id, string $classroom, $turn, string $course): array
    {
        $result = $this->repository->check_in_time_of_teacher_exists($teacher_id, $classroom, $turn, $course);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['exists']) {
            return $this->repository->register_check_in_time_of_teacher($teacher_id, $classroom, $turn, $course);
        }
        return $this->repository->register_check_out_time_of_teacher($teacher_id, $classroom, $turn, $course);
    }
}