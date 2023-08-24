<?php

namespace modules\students\application;

use modules\students\domain\Repository;

final class EnrollStudentInGroup
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $student_code, string $course_code, int $section) : array
    {
        return $this->repository->enroll($student_code, $course_code, $section);
    }
}