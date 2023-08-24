<?php

namespace modules\sections\application;

use modules\sections\domain\Repository;

final class GetSectionByCourseCodeAndNumber
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $course_code, int $section): array
    {
        return $this->repository->read_by_course_and_number_section($course_code, $section);
    }
}