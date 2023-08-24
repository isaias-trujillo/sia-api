<?php

namespace modules\groups\application;

use modules\groups\domain\Repository;

final class GetGroupById
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $group_id) : array
    {
        return $this->repository->find_by_id($group_id);
    }
}