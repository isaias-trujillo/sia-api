<?php

namespace api\controllers;

use api\core\Request;
use modules\auth\infrastructure\AuthService;
use modules\groups\infrastructure\GroupsService;
use modules\teachers\infrastructure\TeacherService;

final class Groups
{
    private function __construct()
    {
    }

    public static function get_students(Request $request)
    {
        $parameters = $request->parameters();
        if (!isset($parameters['groupId'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay id del grupo.'
            ]);
            return;
        }
        $group_id = $parameters['groupId'];
        $service = new GroupsService();
        $result = $service->get_students($group_id);
        if (!$result['success']) {
            echo json_encode($result);
            return;
        }
        if (!$result['found']) {
            echo json_encode($result);
            return;
        }
        echo json_encode($result);
    }
}