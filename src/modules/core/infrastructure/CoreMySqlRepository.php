<?php

namespace modules\core\infrastructure;

use modules\core\application\courses\StandardizeCareers;
use modules\core\application\courses\StandardizeCourses;
use modules\core\application\courses\StandardizeGroups;
use modules\core\application\courses\StandardizeSections;
use modules\core\application\courses\StandardizeSectionsByGroup;
use modules\core\application\courses\StandardizeStudyPlans;
use modules\core\application\courses\StandardizeTeachers;
use modules\core\application\enrollments\StandardizeEnrollments;
use modules\core\application\enrollments\StandardizeStudents;
use modules\core\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

final class CoreMySqlRepository extends MySqlRepository implements Repository
{
    function standardize_course_data(array $records): array
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
                'records' => $this->processed_course_data($records)
            ];
        };
        return $this->query($controller);
    }

    function processed_course_data(array $records): array
    {
        return array_map(function (array $row) {
            return [
                'course code' => $row['course code'],
                'section' => $row['section'],
                'section id' => $row['section id'],
            ];
        }, $records);
    }

    function standardize_enrollment_data(array $records): array
    {
        $total = count($records);
        $students = StandardizeStudents::run($records);
        $enrollments = StandardizeEnrollments::run($records);

        $overview = [
            'students' => $students,
            'enrollments' => $enrollments,
            'records' => $this->parse_enrollment_data($records)
        ];

        $controller = function () use ($records, $overview, $total) {
            return [
                'success' => true,
                'message' => "Se han procesado $total registros.",
                'overview' => $overview,
            ];
        };
        return $this->query($controller);
    }

    function parse_enrollment_data(array $records) : array
    {
        return array_map(function (array $row) {
            return [
                'student code' => $row['student code'] ?? null,
                'student id' => $row['student id'] ?? null,
                'enrollment id' => $row['enrollment id'] ?? null,
            ];
        }, $records);
    }
}