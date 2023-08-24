<?php

namespace modules\courses\application;

use modules\courses\domain\Course;
use modules\courses\domain\Repository;

final class CreateCourse
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(Course $course) : array
    {
       return $this->repository->save($course);
    }
}