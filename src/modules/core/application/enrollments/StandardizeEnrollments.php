<?php

namespace modules\core\application\enrollments;

use modules\students\infrastructure\StudentsService;

final class StandardizeEnrollments
{
    public static function run(array &$records): array
    {
        $created = 0;
        $items = self::create_enrollments($records, $created);

        foreach ($records as &$record) {
            $key = $record['student code'] . " in " . $record['course code'] . " of " . $record['section'];
            $id = $items[$key] ?? null;
            $record['enrollment id'] = $id;
        }

        return ['total' => count($records), 'created' => $created];
    }

    private static function create_enrollments(array $enrollments, int &$created): array
    {
        $items = [];
        $service = new StudentsService();
        foreach ($enrollments as $enrollment) {
            $result = $service->enroll(
                $enrollment['student code'],
                $enrollment['course code'],
                intval($enrollment['section'])
            );
            if (!$result['success']) {
                continue;
            }
            $key = $enrollment['student code'] . " in " . $enrollment['course code'] . " of " . $enrollment['section'];
            if ($result['created']) {
                $items[$key] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find_enroll(
                $enrollment['student code'],
                $enrollment['course code'],
                intval($enrollment['section'])
            );
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$key] = $result['enrollment']['id'];
        }
        return $items;
    }
}