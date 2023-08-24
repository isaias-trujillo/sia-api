<?php

namespace modules\core\application\courses;

use modules\groups\infrastructure\GroupsService;

final class StandardizeSectionsByGroup
{
    public static function run(array &$records): array
    {
        $sections_by_group = self::get_sections_by_group($records);
        $created = 0;
        $items = self::create_sections_by_group($sections_by_group, $created);

        foreach ($records as &$record) {
            $key = $record['section id'] . " by " . $record['group id'];
            $id = $items[$key] ?? null;
            $record['section by group id'] = $id;
        }

        return ['total' => count($sections_by_group), 'created' => $created];
    }

    private static function record_to_section_by_group(array $record): array
    {
        return [
            'section id' => $record['section id'],
            'group id' => $record['group id']
        ];
    }

    private static function get_sections_by_group(array $records): array
    {
        $groups = [];
        foreach ($records as $record) {
            $groups[] = self::record_to_section_by_group($record);
        }
        return $groups;
    }

    private static function create_sections_by_group(array $sections_by_group, &$created = 0): array
    {
        $items = [];
        $service = new GroupsService();
        foreach ($sections_by_group as $section_by_group) {
            if (!$section_by_group['group id']) {
                continue;
            }
            $result = $service->link(
                intval($section_by_group['section id']),
                intval($section_by_group['group id'])
            );
            $key = $section_by_group['section id'] . " by " . $section_by_group['group id'];
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$key] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->read_link(intval($section_by_group['section id']), intval($section_by_group['group id']));
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$key] = $result['section by group']['id'];
        }
        return $items;
    }
}