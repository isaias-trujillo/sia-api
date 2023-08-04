<?php

namespace api\routes;

use api\core\App;
use api\core\Route;
use api\core\Router;
use modules\teachers\domain\Teacher;
use modules\teachers\infrastructure\TeacherService;

final class Index
{
    static function load(App $app)
    {
        $router = new Router();
        $router->get('/', function (){
            $service = new TeacherService();
            $result = $service->findByDNI("08704900");
            echo json_encode($result);
        });
        $app->add($router);
    }
}