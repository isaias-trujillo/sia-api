<?php

namespace modules\teachers\application;

use modules\teachers\domain\Repository;

final class GetTeacherByDNI
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $dni): array
    {
        return $this->repository->read($dni);
    }
}