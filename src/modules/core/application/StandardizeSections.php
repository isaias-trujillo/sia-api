<?php

namespace modules\core\application;

use modules\courses\domain\Course;
use modules\courses\infrastructure\CoursesService;
use modules\sections\infrastructure\SectionsService;

final class StandardizeSections
{
    public static function run(array &$records): array
    {
        $sections = self::get_sections($records);
        $created = 0;
        $items = self::create_sections($sections, $created);

        foreach ($records as &$record) {
            $key = $record['section']." by ".$record['course id'];
            $id = $items[$key];
            $record['section id'] = $id;
        }

        return ['total' => count($sections), 'created' => $created];
    }

    private static function record_to_section(array $record): array
    {
        return [
            'number' => $record['section'],
            'course id' => $record['course id'],
            'student limit' => $record['student limit'],
        ];
    }

    private static function get_sections(array $records): array
    {
        $sections = [];
        foreach ($records as $record) {
            $matches = array_filter($sections, function (array $section) use ($record) {
                return $section['number'] == $record['section']
                    && $section['course id'] == $record['course id'];
            });
            if (count($matches) >= 1) {
                continue;
            }
            $sections[] = self::record_to_section($record);
        }
        return $sections;
    }

    private static function create_sections(array $sections, &$created = 0): array
    {
        $items = [];
        $service = new SectionsService();
        foreach ($sections as $section) {
            $result = $service->create(
                intval($section['number']),
                intval($section['course id']),
                intval($section['student limit'])
            );
            $key = $section['number']." by ".$section['course id'];
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$key] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find(intval($section['number']), intval($section['course id']));
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$key] = $result['section']['id'];
        }
        return $items;
    }
}