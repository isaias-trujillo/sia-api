<?php

namespace api\core;

use api\routes\Core;

final class Endpoints {
    static function load(App $app)
    {
        Core::load($app);
    }
}