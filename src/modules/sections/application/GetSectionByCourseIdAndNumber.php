<?php

namespace modules\sections\application;

use modules\sections\domain\Repository;

final class GetSectionByCourseIdAndNumber
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $number, int $course_id): array
    {
        return $this->repository->read($number, $course_id);
    }
}