<?php

namespace api\core;

use api\routes\Core;
use api\routes\Index;

final class Endpoints {
    static function load(App $app)
    {
        Index::load($app);
        Core::load($app);
    }
}