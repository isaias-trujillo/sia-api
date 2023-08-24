<?php

namespace modules\auth\application;

use modules\auth\domain\Repository;

class CheckIfTeacherExists
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $dni): array
    {
       return $this->repository->exists($dni);
    }
}