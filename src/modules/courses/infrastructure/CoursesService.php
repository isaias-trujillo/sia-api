<?php

namespace modules\courses\infrastructure;

use modules\courses\application\CreateCourse;
use modules\courses\application\GetCourse;
use modules\courses\domain\Course;

final class CoursesService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new CourseMySqlRepository();
    }

    public function create(Course $course): array
    {
        $handler = new CreateCourse($this->repository);
        return $handler($course);
    }

    public function find(Course $course): array
    {
        $handler = new GetCourse($this->repository);
        return $handler($course);
    }
}