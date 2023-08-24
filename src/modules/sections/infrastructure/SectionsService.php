<?php

namespace modules\sections\infrastructure;

use modules\sections\application\CreateSection;
use modules\sections\application\GetSectionByCourseIdAndNumber;
use modules\sections\application\GetSectionByCourseCodeAndNumber;

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
    public function find_by_course_code_and_section_number(string $course_code, int $section): array
    {
        $handler = new GetSectionByCourseCodeAndNumber($this->repository);
        return $handler($course_code, $section);
    }
}