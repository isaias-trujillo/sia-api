<?php

namespace modules\clock\infrastructure;

use modules\clock\application\GetTimeInfo;

class ClockService
{
    private $repository;

    public function __construct(){
        $this->repository = new ClockMySqlRepository();
    }

    public function get_time_info(): array
    {
        $handler = new GetTimeInfo($this->repository);
        return $handler();
    }
}