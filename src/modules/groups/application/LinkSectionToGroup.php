<?php

namespace modules\groups\application;

use modules\groups\domain\Repository;

final class LinkSectionToGroup
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $section_id, int $group_id) : array
    {
        return $this->repository->link($section_id, $group_id);
    }
}