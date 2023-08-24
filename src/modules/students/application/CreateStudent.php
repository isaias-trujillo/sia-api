<?php

namespace modules\students\application;

use modules\students\domain\Repository;

final class CreateStudent
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $student) : array
    {
        return $this->repository->save($student);
    }
}