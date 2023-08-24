<?php

namespace modules\core\infrastructure;

use modules\core\application\StandardizeCourseData;
use modules\core\application\StandardizeEnrollmentData;

final class CoreService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new CoreMySqlRepository();
    }


    function save_course_data(array $records): array
    {
        $standardize_course_data = new StandardizeCourseData($this->repository);
        return $standardize_course_data($records);
    }

    function save_enrollments_data(array $records): array
    {
        $standardize_enrollment_data = new StandardizeEnrollmentData($this->repository);
        return $standardize_enrollment_data($records);
    }
}