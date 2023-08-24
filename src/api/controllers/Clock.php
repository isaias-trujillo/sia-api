<?php

namespace api\controllers;

use api\core\Request;
use modules\auth\infrastructure\AuthService;
use modules\clock\infrastructure\ClockService;

final class Clock
{
    private function __construct()
    {
    }

    public static function current_timestamp(Request $request)
    {
        $service = new ClockService();
        $result = $service->get_time_info();
        echo json_encode($result);
    }
}