<?php

namespace modules\study_plans\application;

use modules\study_plans\domain\Repository;
use modules\study_plans\domain\StudyPlan;

final class CreateStudyPlan
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(StudyPlan $plan) : array
    {
        return $this->repository->create($plan);
    }
}