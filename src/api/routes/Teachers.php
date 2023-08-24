<?php

namespace api\routes;

use api\core\App;
use api\core\Router;

final class Teachers
{
    static function load(App $app)
    {
        $router = new Router();
        $router->get('/:dni/groups', "\\api\\controllers\\Teachers::get_groups");
        $router->get('/:dni/groups/:groupId/students', "\\api\\controllers\\Teachers::get_students_of_group_by_id");
        $app->add_on('/teachers', $router);
    }
}