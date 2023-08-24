<?php

namespace modules\clock\application;

use modules\clock\domain\Repository;

final class GetTimeInfo
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(): array
    {
        return $this->repository->get_time_info();
    }
}