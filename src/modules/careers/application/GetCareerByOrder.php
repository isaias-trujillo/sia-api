<?php

namespace modules\careers\application;

use modules\careers\domain\Career;
use modules\careers\domain\Repository;

final class GetCareerByOrder
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $order): array
    {
        return $this->repository->read($order);
    }
}