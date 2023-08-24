<?php

namespace modules\courses\application;

use modules\courses\domain\Course;
use modules\courses\domain\Repository;

final class GetCourse
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Course $course) : array
    {
       return $this->repository->find($course);
    }
}