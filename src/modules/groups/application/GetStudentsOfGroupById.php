<?php

namespace modules\groups\application;

use modules\groups\domain\Repository;

final class GetStudentsOfGroupById
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $group_id) : array
    {
        return $this->repository->get_students($group_id);
    }
}