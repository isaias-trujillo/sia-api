<?php

namespace modules\core\application;

use modules\groups\infrastructure\GroupsService;

final class StandardizeGroups
{
    public static function run(array &$records): array
    {
        $sections = self::get_groups($records);
        $created = 0;
        $items = self::create_groups($sections, $created);

        foreach ($records as &$record) {
            $key = $record['classroom and turn'] . " " . $record['course'];
            $id = $items[$key];
            $record['group id'] = $id;
        }

        return ['total' => count($sections), 'created' => $created];
    }

    private static function record_to_group(array $record): array
    {
        return [
            'classroom and turn' => $record['classroom and turn'],
            'course' => $record['course'],
            'teacher id' => $record['teacher id'],
            'section id' => $record['section id'],
        ];
    }

    private static function get_groups(array $records): array
    {
        $groups = [];
        foreach ($records as $record) {
            $matches = array_filter($groups, function (array $group) use ($record) {
                return $group['classroom and turn'] == $record['classroom and turn']
                    && $group['course'] == $record['course'];
            });
            if (count($matches) >= 1) {
                continue;
            }
            $groups[] = self::record_to_group($record);
        }
        return $groups;
    }

    private static function create_groups(array $groups, &$created = 0): array
    {
        $turns = ['M' => 1, 'T' => 2, 'N' => 3];
        $items = [];
        $service = new GroupsService();
        foreach ($groups as $group) {
            $turn_id = null;
            $turn = substr($group['classroom and turn'], -1);
            if (preg_grep('(\d{3}-([MTN]))', explode("\n", $group['classroom and turn']))) {
                $turn_id = $turns[$turn] ?? null;
            }
            $classroom = $group['classroom and turn'];
            if ($turn_id) {
                $classroom = str_replace("-$turn", "", $classroom);
            }
            $result = $service->create(
                $classroom,
                $turn_id,
                $group['course'],
                intval($group['teacher id']),
                intval($group['section id'])
            );
            $key = $group['classroom and turn'] . " " . $group['course'];
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$key] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find($classroom, $turn_id, $group['course']);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$key] = $result['group']['id'];
        }
        return $items;
    }
}