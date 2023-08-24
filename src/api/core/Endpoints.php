<?php

namespace api\core;

use api\routes\Attendances;
use api\routes\Auth;
use api\routes\Clock;
use api\routes\Core;
use api\routes\Teachers;

final class Endpoints {
    static function load(App $app)
    {
        Core::load($app);
        Auth::load($app);
        Teachers::load($app);
        Clock::load($app);
        Attendances::load($app);
    }
}