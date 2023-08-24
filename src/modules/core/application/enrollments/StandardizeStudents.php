<?php

namespace modules\core\application\enrollments;

use modules\students\infrastructure\StudentsService;

final class StandardizeStudents
{
    public static function run(array &$records): array
    {
        $students = self::get_students($records);
        $created = 0;
        $items = self::create_students($students, $created);

        foreach ($records as &$record){
            $id = $items[$record['student code']];
            $record['student id'] = $id;
        }

        return ['total' => count($students), 'created' => $created];
    }

    private static function get_students(array $records): array
    {
        $codes = array_values(array_unique(array_map(function (array $record) {
            return $record['student code'];
        }, $records)));
        return array_filter(array_map(function (string $code) use ($records) {
            return array_values(array_filter($records, function (array $record) use ($code) {
                return $record['student code'] == $code;
            }))[0] ?? null;
        }, $codes));
    }

    private static function create_students(array $students, int &$created): array
    {
        $items = [];
        $service = new StudentsService();
        foreach ($students as $student) {
            $student_code = $student['student code'];
            $result = $service->create($student);
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$student_code] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find($student_code);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$student_code] = $result['student']['id'];
        }
        return $items;
    }
}