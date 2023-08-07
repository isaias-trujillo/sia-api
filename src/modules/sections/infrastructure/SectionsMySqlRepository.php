<?php

namespace modules\sections\infrastructure;

use modules\sections\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class SectionsMySqlRepository extends MySqlRepository implements Repository
{

    function exists(int $number, int $course_id): array
    {
        $query = "select * from sections where number=? and course_id=?";
        $message = [
            'error' => "La sección $number no existe.",
            'success' => "Se ha encontrado la sección $number."
        ];
        $parameters = ['types' => 'ii', 'values' => [$number, $course_id]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function create(int $number, int $course_id, int $student_limit): array
    {
        $result = $this->exists($number, $course_id);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe la sección $number del curso con id $course_id"];
        }
        $query = "insert into sections(number, course_id, student_limit) values(?, ?, ?)";
        $parameters = [
            'types' => "iii",
            'values' => [$number, $course_id, $student_limit]
        ];
        $message = [
            'error' => "No se pudo registrar la sección.",
            'success' => "Se ha registrado la sección $number del curso con id $course_id"
        ];
        return $this->insert($query, $parameters, $message);
    }

    function read(int $number, int $course_id): array
    {
        $query = "select * from sections where number=? and course_id=?";
        $message = [
            'error' => "La sección $number no existe.",
            'success' => "Se ha encontrado la sección $number."
        ];
        $parameters = ['types' => 'ii', 'values' => [$number, $course_id]];
        $result = $this->select($query, $message, $parameters);
        $result['section'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}