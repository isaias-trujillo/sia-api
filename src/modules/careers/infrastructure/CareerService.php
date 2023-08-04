<?php

namespace modules\careers\infrastructure;

use modules\careers\application\CreateCareer;
use modules\careers\application\GetCareerByOrder;
use modules\careers\domain\Career;
use modules\teachers\domain\Teacher;

final class CareerService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new CareersMySqlRepository();
    }

    function create(Career $career): array
    {
        $handler = new CreateCareer($this->repository);
        return $handler($career);
    }

    function find_by_order(string $order): array
    {
        $handler = new GetCareerByOrder($this->repository);
        return $handler(intval($order));
    }
}