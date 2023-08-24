<?php

namespace api\routes;

use api\core\App;
use api\core\Router;

final class Attendances
{
    static function load(App $app)
    {
        $router = new Router();
        $router->get('/groups/:groupId', "\\api\\controllers\\Attendances::get_attendance_of_group_by_id");
        $router->post('/groups/:groupId', "\\api\\controllers\\Attendances::register_attendance_of_group_by_id");
        $router->get('/teacher/:teacher/groups/:groupId', "\\api\\controllers\\Attendances::get_attendance_of_teacher");
        $router->post('/teacher/:teacher/groups/:groupId', "\\api\\controllers\\Attendances::register_attendance_of_teacher");
        $app->add_on('/attendances', $router);
    }
}