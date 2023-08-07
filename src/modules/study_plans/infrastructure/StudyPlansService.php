<?php

namespace modules\study_plans\infrastructure;

use modules\study_plans\application\CreateStudyPlan;
use modules\study_plans\application\GetStudyPlanByYearAndCareerId;
use modules\study_plans\domain\StudyPlan;

class StudyPlansService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new StudyPlansMySqlRepository();
    }

    public function create(StudyPlan $plan): array
    {
        $handler = new CreateStudyPlan($this->repository);
        return $handler($plan);
    }

    public function find_by_year_and_career_id(int $year, int $career_id): array
    {
        $handler = new GetStudyPlanByYearAndCareerId($this->repository);
        return $handler($year, $career_id);
    }
}