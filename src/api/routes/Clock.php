<?php

namespace api\routes;

use api\core\App;
use api\core\Router;

final class Clock
{
    static function load(App $app)
    {
        $router = new Router();
        $router->get('/', "\\api\\controllers\\Clock::current_timestamp");
        $app->add_on('/clock', $router);
    }
}