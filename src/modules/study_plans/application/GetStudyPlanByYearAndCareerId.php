<?php

namespace modules\study_plans\application;

use modules\study_plans\domain\Repository;
use modules\study_plans\domain\StudyPlan;

final class GetStudyPlanByYearAndCareerId
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $year, int $career_id) : array
    {
        return $this->repository->read($year, $career_id);
    }
}