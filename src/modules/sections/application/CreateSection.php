<?php

namespace modules\sections\application;

use modules\sections\domain\Repository;

final class CreateSection
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $number, int $course_id, int $student_limit): array
    {
        return $this->repository->create($number, $course_id, $student_limit);
    }
}