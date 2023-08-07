<?php

namespace modules\core\application;

use modules\teachers\domain\Teacher;
use modules\teachers\infrastructure\TeacherService;

final class StandardizeTeachers
{
    public static function run(array &$records): array
    {
        $teachers = self::get_teachers($records);
        $created = 0;
        $items = self::create_teachers($teachers, $created);

        foreach ($records as &$record){
            $id = $items[$record['dni']];
            $record['career id'] = $record['career'];
            $record['study plan id'] = $record['study plan'];
            $record['teacher id'] = $id;
        }

        return ['total' => count($teachers), 'created' => $created];
    }

    private static function get_teachers(array $records): array
    {
        $dni_list = array_values(array_unique(array_map(function (array $record) {
            return $record['dni'];
        }, $records)));
        return array_filter(array_map(function (string $dni) use ($records) {
            $match = array_values(array_filter($records, function (array $record) use ($dni) {
                return $record['dni'] == $dni;
            }))[0] ?? null;
            if (!$match) {
                return null;
            }
            return new Teacher(
                $match['paternal surname'],
                $match['maternal surname'],
                $match['firstname'],
                $match['dni']
            );
        }, $dni_list));
    }

    private static function create_teachers(array $teachers, int &$created): array
    {
        $items = [];
        $service = new TeacherService();
        foreach ($teachers as $teacher) {
            $dni = $teacher->dni();
            $result = $service->create($teacher);
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
        return $items;
    }
}