<?php

namespace api\controllers;

use api\core\Request;
use modules\attendances\infrastructure\AttendanceService;
use modules\auth\infrastructure\AuthService;
use modules\groups\infrastructure\GroupsService;
use modules\teachers\infrastructure\TeacherService;

final class Attendances
{
    private function __construct()
    {
    }

    public static function get_attendance_of_group_by_id(Request $request)
    {
        $parameters = $request->parameters();

        if (!isset($parameters['groupId'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay id del grupo'
            ]);
            return;
        }

        $group_id = $parameters['groupId'];
        $service = new AttendanceService();
        $result = $service->get_attendance_of_group_by_id(intval($group_id));
        echo json_encode($result);
    }

    public static function register_attendance_of_group_by_id(Request $request)
    {
        $parameters = $request->parameters();
        if (!isset($parameters['groupId'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay id del grupo.'
            ]);
            return;
        }

        $body = $request->body();
        if (!isset($body['teacher'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay DNI del profesor.'
            ]);
            return;
        }

        if (!isset($body['attendances']) || count($body['attendances']) == 0){
            echo json_encode([
                'success' => false,
                'message' => 'No hay asistencias que registrar.'
            ]);
            return;
        }

        $dni = $body['teacher'];
        $group_id = $parameters['groupId'];
        $service = new AttendanceService();
        $result = $service->register_attendance_of_group_by_id($dni, intval($group_id), $body['attendances']);
        echo json_encode($result);
    }

    public static function get_attendance_of_teacher(Request $request){
        $parameters = $request->parameters();
        if (!isset($parameters['teacher'])) {
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
        $dni = $parameters['teacher'];
        $group_id = $parameters['groupId'];
        $service = new AttendanceService();
        $result = $service->get_attendance_of_teacher($dni, intval($group_id));
        echo json_encode($result);
    }
    public static function register_attendance_of_teacher(Request $request){
        $parameters = $request->parameters();
        if (!isset($parameters['teacher'])) {
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
        $dni = $parameters['teacher'];
        $group_id = $parameters['groupId'];
        $service = new AttendanceService();
        $result = $service->register_attendance_of_teacher($dni, intval($group_id));
        echo json_encode($result);
    }
}