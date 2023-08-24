<?php

namespace modules\auth\infrastructure;

use modules\auth\application\CheckIfTeacherExists;
use modules\auth\application\GetTeacherByDNI;

class AuthService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new AuthMySqlRepository();
    }

    public function exists(string $dni) : array{
        $handler = new CheckIfTeacherExists($this->repository);
        return $handler($dni);
    }

    public function find(string $dni) : array{
        $handler = new GetTeacherByDNI($this->repository);
        return $handler($dni);
    }
}