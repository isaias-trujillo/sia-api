<?php

namespace api\controllers;

use api\core\Request;
use modules\auth\infrastructure\AuthService;
use modules\groups\infrastructure\GroupsService;
use modules\teachers\infrastructure\TeacherService;

final class Teachers
{
    private function __construct()
    {
    }

    public static function get_groups(Request $request)
    {
        $parameters = $request->parameters();
        if (!isset($parameters['dni'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay DNI del profesor.'
            ]);
            return;
        }
        $dni = $parameters['dni'];
        $service = new TeacherService();
        $result = $service->findByDNI($dni);
        if (!$result['success']) {
            echo json_encode($result);
            return;
        }
        if (!$result['found']) {
            echo json_encode($result);
            return;
        }
        $result = $service->get_groups($dni);
        echo json_encode($result);
    }

    public static function get_students_of_group_by_id(Request $request){
        $parameters = $request->parameters();
        if (!isset($parameters['dni'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay DNI del profesor.'
            ]);
            return;
        }
        if (!isset($parameters['groupId'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay id del grupo.'
            ]);
            return;
        }
        $dni = $parameters['dni'];
        $teacher_service = new TeacherService();
        $result = $teacher_service->findByDNI($dni);
        if (!$result['success']) {
            echo json_encode($result);
            return;
        }
        if (!$result['found']) {
            echo json_encode($result);
            return;
        }
        $groupId = $parameters['groupId'];
        $group_service = new GroupsService();
        $result = $group_service->get_students($groupId);
        echo json_encode($result);
    }
}