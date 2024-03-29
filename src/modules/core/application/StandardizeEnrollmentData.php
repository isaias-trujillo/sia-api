<?php

namespace modules\core\application;

use modules\core\domain\Repository;

final class StandardizeEnrollmentData
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $records): array
    {
        return $this->repository->standardize_enrollment_data($records);
    }
}