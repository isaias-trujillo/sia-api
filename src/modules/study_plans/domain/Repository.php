<?php

namespace modules\study_plans\domain;

use modules\careers\domain\Career;

interface Repository
{
    function exists(int $year, int $career_id) : array;
    function create(StudyPlan $plan) : array;
    function read(int $year, int $career_id) : array;
}