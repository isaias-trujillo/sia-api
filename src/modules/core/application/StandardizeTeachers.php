<?php

namespace modules\core\application;

use modules\teachers\domain\Teacher;
use modules\teachers\infrastructure\TeacherService;

final class StandardizeTeachers
{
    public static function run(array &$records): array
    {
        $dni_list = array_values(array_unique(array_map(function (Request $request) {
            return $request->dni();
        }, $records)));
        $teachers = array_filter(array_map(function (string $dni) use ($records) {
            $match = array_values(array_filter($records, function (Request $request) use ($dni) {
                return $request->dni() == $dni;
            }))[0] ?? null;
            if (!$match) {
                return null;
            }
            return new Teacher(
                $match->paternal_surname(),
                $match->maternal_surname(),
                $match->firstname(),
                $match->dni()
            );
        }, $dni_list));

        $created = 0;
        $items = [];
        $service = new TeacherService();
        foreach ($teachers as $teacher) {
            $dni = $teacher->dni();
            $result = $service->create($teacher);
            //$records = $result;
            //return ['total' => count($teachers), 'created' => 0];
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$dni] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->findByDNI($dni);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$dni] = $result['teacher']['id'];
        }
        $records = array_map(function (Request $request) use ($items) {
            $id = $items[$request->dni()];
            return [
                'classroom_and_turn' => $request->classroom_and_turn(),
                'course_code' => $request->course_code(),
                'course' => $request->course(),
                'section' => $request->section(),
                'career' => $request->career(),
                'teacherId' => $id
            ];
        }, $records);

        return ['total' => count($teachers), 'created' => $created];
    }
}