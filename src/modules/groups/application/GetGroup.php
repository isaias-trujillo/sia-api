<?php

namespace modules\groups\application;

use modules\groups\domain\Repository;

final class GetGroup
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $classroom, $turn_id, string $course_name) : array
    {
        return $this->repository->read($classroom, $turn_id, $course_name);
    }
}