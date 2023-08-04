<?php

namespace api\routes;

use api\core\App;
use api\core\Router;

final class Core
{
    static function load(App $app)
    {
        $router = new Router();
        $router->post('/', "api\\controllers\\Core::update");
        $app->add_on('/core', $router);
    }
}