<?php

namespace modules\groups\infrastructure;

use modules\groups\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class GroupsMySqlRepository extends MySqlRepository implements Repository
{
    function exists(string $classroom, $turn_id, string $course_name): array
    {
        $query = "select g.id from `groups` g 
                    join sections_by_group sbg on g.id = sbg.group_id 
                    join sections s on s.id = sbg.section_id
                    join courses c on s.course_id = c.id
                    where g.classroom = ? 
                      and (g.turn_id = ? or g.turn_id is null)
                      and c.name = ?";
        $message = [
            'error' => "El grupo $classroom de turno con id `$turn_id` del curso $course_name no existe.",
            'success' => "Se ha encontrado el grupo $classroom de turno con id `$turn_id` del curso $course_name."
        ];
        $parameters = ['types' => 'sis', 'values' => [$classroom, $turn_id, $course_name]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function create(string $classroom, $turn_id, string $course_name, int $teacher_id, int $section_id): array
    {
        $result = $this->exists($classroom, $turn_id, $course_name);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe el grupo $classroom de turno ($turn_id) del curso $course_name."];
        }
        $query = "insert into `groups`(classroom, turn_id, teacher_id) values (?, ?, ?)";
        $parameters = [
            'types' => "sii",
            'values' => [$classroom, $turn_id, $teacher_id]
        ];
        $message = [
            'error' => "No se pudo registrar el grupo",
            'success' => "Se ha registrado el grupo $classroom de turno ($turn_id) del curso $course_name."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function read(string $classroom, $turn_id, string $course_name): array
    {
        $query = "
                    select g.id as 'id', g.classroom as 'classroom', t.abbreviation as 'turn', c.name
                    from `groups` g
                        left join turns t on t.id = g.turn_id
                             join sections_by_group sbg on g.id = sbg.group_id
                             join sections s on s.id = sbg.section_id
                             join courses c on s.course_id = c.id
                    where g.classroom = ?
                      and (g.turn_id = ? or g.turn_id is null)
                      and c.name = ?"
        ;

        $message = [
            'error' => "El grupo $classroom de turno ($turn_id) del curso $course_name.",
            'success' => "Se ha encontrado el grupo $classroom de turno ($turn_id) del curso $course_name."
        ];
        $parameters = ['types' => 'sis', 'values' => [$classroom, $turn_id, $course_name]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['group'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }

    function link(int $section_id, int $group_id): array
    {
        $result = $this->is_linked($section_id, $group_id);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['linked']) {
            return ['success' => true, 'created' => false, 'message' => "Ya está vinculado la sección con id $section_id al grupo con id $group_id."];
        }
        $query = "insert into sections_by_group(section_id, group_id) values(?, ?)";
        $parameters = [
            'types' => "ii",
            'values' => [$section_id, $group_id]
        ];
        $message = [
            'error' => "No se pudo vincular la sección al grupo.",
            'success' => "Se ha vinculado la sección con id $section_id al grupo con id $group_id."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function is_linked(int $section_id, int $group_id): array
    {
        $query = "select g.id from `groups` g 
                    join sections_by_group sbg on g.id = sbg.group_id 
                    where sbg.section_id = ?
                    and  sbg.group_id = ?";
        $message = [
            'error' => "La sección con id $section_id no está vinculada al grupo con id $group_id.",
            'success' => "La sección con id $section_id está vinculada al grupo con id $group_id."
        ];
        $parameters = ['types' => 'ii', 'values' => [$section_id, $group_id]];
        $result = $this->check_if_exists($query, $message, $parameters);
        $result['linked'] = $result['exists'];
        unset($result['exists']);
        return $result;
    }

    function read_link(int $section_id, int $group_id): array
    {
        $query = "select sbg.id as 'id' from sections_by_group sbg
                    where sbg.section_id = ?
                      and sbg.group_id = ?;
";
        $message = [
            'error' => "La sección con id `$section_id` no está vinculado al grupo con id `$group_id`.",
            'success' => "Se han encontrada la sección con id `$section_id` relacionada al grupo con id `$group_id`."
        ];
        $parameters = ['types' => 'ii', 'values' => [$section_id, $group_id]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['section by group'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }

    function get_students(int $group_id): array
    {
        $query = "
                    select s2.id                                                                    as 'id',
                           s2.code                                                                  as 'code',
                           concat(s2.paternal_surname, ' ', s2.maternal_surname, ' ', s2.firstname) as 'full name',
                           s2.email                                                                 as 'email'
                    from `groups` g
                             left join turns t on g.turn_id = t.id
                             join teachers t2 on g.teacher_id = t2.id
                             join sections_by_group sbg on g.id = sbg.group_id
                             join sections s on sbg.section_id = s.id
                             join courses c on s.course_id = c.id
                             left join enrollments e on s.id = e.section_id
                             left join students s2 on e.student_id = s2.id
                    where group_id = ?
                      and s2.id is not null
                    order by concat(s2.paternal_surname, ' ', s2.maternal_surname, ' ', s2.firstname)
        "
        ;

        $message = [
            'error' => "No hay estudiantes para el grupo con id $group_id.",
            'success' => "Se ha encontrado estudiantes para el grupo con id $group_id."
        ];
        $parameters = ['types' => 'i', 'values' => [$group_id]];
        return $this->retrieve_records($query, $message, $parameters);
    }

    function find_by_id(int $group_id): array
    {
        $query = "
                select g.id as 'id', g.classroom as 'classroom', t.abbreviation as 'turn', c.name as 'course'
                    from `groups` g
                        left join turns t on g.turn_id = t.id
                        join sections_by_group sbg on g.id = sbg.group_id
                        join sections s on sbg.section_id = s.id
                        join courses c on c.id = s.course_id
                    where g.id = ?
                limit 1
                    "
        ;

        $message = [
            'error' => "El grupo con id '$group_id' no existe.",
            'success' => "Se ha encontrado un grupo con id '$group_id'."
        ];
        $parameters = ['types' => 'i', 'values' => [$group_id]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['group'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}