<?php

namespace modules\students\application;

use modules\students\domain\Repository;

final class GetStudentByCode
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $student_code) : array
    {
        return $this->repository->find($student_code);
    }
}