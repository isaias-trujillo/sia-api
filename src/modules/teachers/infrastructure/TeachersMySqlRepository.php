<?php

namespace modules\teachers\infrastructure;

use modules\shared\infrastructure\MySqlRepository;
use modules\teachers\domain\Repository;
use modules\teachers\domain\Teacher;

class TeachersMySqlRepository extends MySqlRepository implements Repository
{
    function exists(string $dni): array
    {
        $query = "select * from teachers where dni=?";
        $message = [
            'error' => "El profesor con $dni no existe.",
            'success' => "Se ha encontrado un profesor con dni $dni."
        ];
        $parameters = ['types' => 's', 'values' => [$dni]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function create(Teacher $teacher): array
    {
        $dni = $teacher->dni();
        $result = $this->exists($dni);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe un profesor con el dni $dni."];
        }
        $query = "insert into teachers(paternal_surname, maternal_surname, firstname, dni) values (?, ?, ?, ?)";
        $parameters = [
            'types' => "ssss",
            'values' => array_values($teacher->to_array())
        ];
        $message = [
            'error' => "No se pudo registrar al profesor.",
            'success' => "Se ha registrado al profesor con dni $dni."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function read(string $dni): array
    {
        $query = "select * from teachers where dni=?";
        $parameters = [
            'types' => "s",
            'values' => [$dni]
        ];
        $message = [
            'error' => "DNI incorrecto.",
            'success' => "Se ha encontrado un profesor con dni $dni."
        ];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['teacher'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }

    function get_groups(string $dni): array
    {
        $query = "
                    select g.id           as 'id',
                           g.classroom    as 'classroom',
                           t.abbreviation as 'turn',
                           c.name         as 'course'
                    from `groups` g
                             left join turns t on g.turn_id = t.id
                             join teachers t2 on g.teacher_id = t2.id
                             join sections_by_group sbg on g.id = sbg.group_id
                             join sections s on sbg.section_id = s.id
                             join courses c on s.course_id = c.id
                    where dni = ?
                    group by concat(g.classroom, ' ', ifnull(t.abbreviation, ''), ' ', c.name)
        ";
        $parameters = [
            'types' => "s",
            'values' => [$dni]
        ];
        $count = 0;
        $message = [
            'error' => "El profesor con dni $dni no existe.",
            'success' => "Se ha encontrado grupos para el profesor con dni $dni."
        ];
        return $this->retrieve_records($query, $message, $parameters, $count);
    }
}