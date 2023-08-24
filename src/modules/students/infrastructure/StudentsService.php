<?php

namespace modules\students\infrastructure;

use modules\students\application\CreateStudent;
use modules\students\application\EnrollStudentInGroup;
use modules\students\application\GetEnrollByStudentAndCourse;
use modules\students\application\GetStudentByCode;

final class StudentsService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new StudentsMySqlRepository();
    }

    function create(array $student): array
    {
        $handler = new CreateStudent($this->repository);
        return $handler($student);
    }

    function find(string $student_code): array
    {
        $handler = new GetStudentByCode($this->repository);
        return $handler($student_code);
    }

    function enroll(string $student_code, string $course_code, int $section): array
    {
        $handler = new EnrollStudentInGroup($this->repository);
        return $handler($student_code, $course_code, $section);
    }


    function find_enroll(string $student_code, string $course_code, int $section): array
    {
        $handler = new GetEnrollByStudentAndCourse($this->repository);
        return $handler($student_code, $course_code, $section);
    }
}