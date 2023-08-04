<?php

namespace modules\teachers\application;

use modules\teachers\domain\Repository;
use modules\teachers\domain\Teacher;

final class CreateTeacher
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Teacher $teacher): array
    {
        return $this->repository->create($teacher);
    }
}