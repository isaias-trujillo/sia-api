<?php

namespace modules\core\infrastructure;

use modules\core\application\StandardizeCareers;
use modules\core\application\StandardizeCourses;
use modules\core\application\StandardizeGroups;
use modules\core\application\StandardizeSections;
use modules\core\application\StandardizeSectionsByGroup;
use modules\core\application\StandardizeStudyPlans;
use modules\core\application\StandardizeTeachers;
use modules\core\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

final class CoreMySqlRepository extends MySqlRepository implements Repository
{
    function standardize(array $records): array
    {
        $total = count($records);
        $careers = StandardizeCareers::run($records);
        $study_plans = StandardizeStudyPlans::run($records);
        $teachers = StandardizeTeachers::run($records);
        $courses = StandardizeCourses::run($records);
        $sections = StandardizeSections::run($records);
        $groups = StandardizeGroups::run($records);
        $sections_by_group = StandardizeSectionsByGroup::run($records);

        $overview = [
            'careers' => $careers,
            'study plans' => $study_plans,
            'teachers' => $teachers,
            'courses' => $courses,
            'sections' => $sections,
            'groups' => $groups,
            'sections_by_group' => $sections_by_group,
        ];

        $controller = function () use ($records, $overview, $total) {
            return [
                'success' => true,
                'message' => "Se han procesado $total registros.",
                'overview' => $overview,
                'records' => $this->parsed_records($records)
            ];
        };
        return $this->query($controller);
    }

    function parsed_records(array $records): array
    {
        return array_map(function (array $row) {
            $keys = ['career', 'student limit', 'study plan', 'cycle', 'credits', 'paternal surname', 'maternal surname', 'firstname', 'dni'];
            $copy = $row;
            foreach ($keys as $key){
                unset($copy[$key]);
            }
            return $copy;
        }, $records);
    }
}