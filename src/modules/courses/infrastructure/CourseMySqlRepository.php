<?php

namespace modules\courses\infrastructure;

use modules\courses\domain\Course;
use modules\courses\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class CourseMySqlRepository extends MySqlRepository implements Repository
{
    function exists(Course $course): array
    {
        $query = "select * from courses where study_plan_id=? and cycle = ? and code=? and name=? and credits=?";
        $message = [
            'error' => "No existe el curso $course",
            'success' => "Se ha encontrado el curso $course.",
        ];
        $parameters = ['types' => 'iissi', 'values' => array_values($course->to_array())];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function save(Course $course): array
    {
        $name = $course->name();
        $result = $this->exists($course);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe el curso $course."];
        }
        $query = "insert into courses(study_plan_id, cycle, code, name, credits) values (?, ?, ?, ?, ?)";
        $parameters = [
            'types' => "iissi",
            'values' => array_values($course->to_array())
        ];
        $message = [
            'error' => "No se pudo registrar el curso.",
            'success' => "Se ha registrado el curso $name."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function find(Course $course): array
    {
        $query = "select * from courses where study_plan_id=? and cycle = ? and code=? and name=? and credits=?";
        $message = [
            'error' => "No existe el curso $course.",
            'success' => "Se ha encontrado el curso $course.",
        ];
        $parameters = ['types' => 'iissi', 'values' => array_values($course->to_array())];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['course'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}