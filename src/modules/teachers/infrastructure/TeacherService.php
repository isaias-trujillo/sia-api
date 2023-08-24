<?php

namespace modules\teachers\infrastructure;

use modules\teachers\application\CreateTeacher;
use modules\teachers\application\GetGroupsOfTeacherByDNI;
use modules\teachers\application\GetTeacherByDNI;
use modules\teachers\domain\Teacher;

final class TeacherService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new TeachersMySqlRepository();
    }

    function create(Teacher $teacher): array
    {
        $controller = new CreateTeacher($this->repository);
        return $controller($teacher);
    }

    function findByDNI(string $dni): array
    {
        $controller = new GetTeacherByDNI($this->repository);
        return $controller($dni);
    }

    function get_groups(string $dni) : array {
        $handler = new GetGroupsOfTeacherByDNI($this->repository);
        return $handler($dni);
    }
}