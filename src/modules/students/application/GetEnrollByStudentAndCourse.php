<?php

namespace modules\students\application;

use modules\students\domain\Repository;

final class GetEnrollByStudentAndCourse
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $student_code, string $course_code, int $section) : array
    {
        return $this->repository->find_enroll($student_code, $course_code, $section);
    }
}