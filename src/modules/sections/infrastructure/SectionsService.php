<?php

namespace modules\sections\infrastructure;

use modules\sections\application\CreateSection;
use modules\sections\application\GetSectionByCourseIdAndNumber;

class SectionsService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new SectionsMySqlRepository();
    }

    public function create(int $number, int $course_id, int $student_limit): array
    {
        $handler = new CreateSection($this->repository);
        return $handler($number, $course_id, $student_limit);
    }

    public function find(int $number, int $course_id): array
    {
        $handler = new GetSectionByCourseIdAndNumber($this->repository);
        return $handler($number, $course_id);
    }
}