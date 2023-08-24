<?php

namespace modules\core\application\courses;

use modules\courses\domain\Course;
use modules\courses\infrastructure\CoursesService;

final class StandardizeCourses
{
    public static function run(array &$records): array
    {
        $courses = self::get_courses($records);
        $created = 0;
        $items = self::create_courses($courses, $created);

        foreach ($records as &$record) {
            $key = (string)self::record_to_course($record);
            $id = $items[$key];
            $record['course id'] = $id;
        }

        return ['total' => count($courses), 'created' => $created];
    }

    private static function record_to_course(array $request): Course
    {
        return new Course(
            intval($request['study plan id']),
            intval($request['cycle']),
            $request['course code'],
            $request['course'],
            intval($request['credits'])
        );
    }

    private static function get_courses(array $records): array
    {
        $courses = [];
        foreach ($records as $record) {
            $matches = array_filter($courses, function (Course $course) use ($record) {
                return $course->study_plan_id() == intval($record['study plan id'])
                    && $course->cycle() == intval($record['cycle'])
                    && $course->code() == $record['course code']
                    && $course->name() == $record['course']
                    && $course->credits() == intval($record['credits']);
            });
            if (count($matches) >= 1) {
                continue;
            }
            $courses[] = self::record_to_course($record);
        }
        return $courses;
    }

    private static function create_courses(array $courses, &$created = 0): array
    {
        $items = [];
        $service = new CoursesService();
        foreach ($courses as $course) {
            $result = $service->create($course);
            $key = (string)$course;
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$key] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find($course);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$key] = $result['course']['id'];
        }
        return $items;
    }
}