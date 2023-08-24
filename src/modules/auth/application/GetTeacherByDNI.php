<?php

namespace modules\auth\application;

use modules\auth\domain\Repository;

class GetTeacherByDNI
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $dni): array
    {
        return $this->repository->find($dni);
    }
}