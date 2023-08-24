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
        return $this->alter_record($query, $parameters, $message);
    }

    function read(int $number, int $course_id): array
    {
        $query = "select * from sections where number=? and course_id=?";
        $message = [
            'error' => "La sección $number no existe.",
            'success' => "Se ha encontrado la sección $number."
        ];
        $parameters = ['types' => 'ii', 'values' => [$number, $course_id]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['section'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }

    function read_by_course_and_number_section(string $course_code, int $section): array
    {
        $query = "    select s.id, s.number, s.student_limit, c.code, c.name, c.cycle, c.credits as 'section id' from `groups`
                        join sections_by_group sbg on `groups`.id = sbg.group_id
                        join sections s on s.id = sbg.section_id
                        join courses c on s.course_id = c.id
                        where c.code = ?
                        and s.number = ?
    ;";
        $message = [
            'error' => "La sección $section no existe para el curso con código $course_code.",
            'success' => "Se ha encontrado la sección $section para el curso con código $course_code"
        ];
        $parameters = ['types' => 'si', 'values' => [$course_code, $section]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['section'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}