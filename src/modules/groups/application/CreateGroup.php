<?php

namespace modules\groups\application;

use modules\groups\domain\Repository;

final class CreateGroup
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $classroom, $turn_id, string $course_name, int $teacher_id, int $section_id) : array
    {
        return $this->repository->create($classroom, $turn_id, $course_name, $teacher_id, $section_id);
    }
}