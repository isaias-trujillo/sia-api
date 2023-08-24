<?php

namespace api\routes;

use api\core\App;
use api\core\Router;

final class Auth
{
    static function load(App $app)
    {
        $router = new Router();
        $router->post('/', "\\api\\controllers\\Auth::login");
        $app->add_on('/auth', $router);
    }
}