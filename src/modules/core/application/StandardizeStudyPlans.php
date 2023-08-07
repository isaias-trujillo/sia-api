<?php

namespace modules\core\application;

use modules\study_plans\domain\StudyPlan;
use modules\study_plans\infrastructure\StudyPlansService;

final class StandardizeStudyPlans
{
    public static function run(array &$records): array
    {
        $study_plans = self::get_study_plans($records);
        $created = 0;
        $items = self::create_study_plans($study_plans, $created);

        foreach ($records as &$record) {
            $key = $record['study plan'] . " by " . $record['career'];
            if (!isset($items[$key])) {
                continue;
            }
            $id = $items[$key];
            $record['study plan'] = $id;
        }
        return ['total' => count($study_plans), 'created' => $created];
    }

    private static function get_study_plans(array $records)
    {
        $career_id_list = array_values(array_unique(array_map(function (array $record) {
            return $record['career'];
        }, $records)));

        return array_merge(...array_values(array_map(function (int $career_id) use ($records) {
            $study_plan_list = array_values(array_unique(array_map(function (array $record) {
                return $record['study plan'];
            }, array_values(array_filter($records, function (array $record) use ($career_id) {
                return $record['career'] == (string)($career_id);
            })))));
            return array_map(function (string $study_plan) use ($career_id) {
                return new StudyPlan(
                    "Plan de estudios $study_plan",
                    intval($study_plan),
                    intval($career_id)
                );
            }, $study_plan_list);
        }, $career_id_list)));
    }

    private static function create_study_plans(array $study_plans, &$created = 0): array
    {
        $items = [];
        $service = new StudyPlansService();
        foreach ($study_plans as $study_plan) {
            $year = $study_plan->year();
            $career_id = $study_plan->career_id();
            $result = $service->create($study_plan);
            if (!$result['success']) {
                continue;
            }
            $key = "$year by $career_id";
            if ($result['created']) {
                $items[$key] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find_by_year_and_career_id($year, $career_id);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$key] = $result['study plan']['id'];
        }
        return $items;
    }
}