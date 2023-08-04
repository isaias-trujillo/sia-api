<?php

namespace modules\careers\application;

use modules\careers\domain\Career;
use modules\careers\domain\Repository;

final class CreateCareer
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Career $career): array
    {
        return $this->repository->create($career);
    }
}