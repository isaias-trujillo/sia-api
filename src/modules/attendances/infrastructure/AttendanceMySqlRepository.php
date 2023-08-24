<?php

namespace modules\attendances\infrastructure;

use modules\attendances\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class AttendanceMySqlRepository extends MySqlRepository implements Repository
{

    function check_in_time_of_teacher_exists(int $teacher_id, string $classroom, $turn, string $course): array
    {
        $query = "
                    select ta.id from teacher_attendances ta 
                        join teachers t on ta.teacher_id = t.id
                        left join turns t2 on ta.turn_id = t2.id
                    where t.id = ?
                    and ta.classroom = ?
                    and (t2.abbreviation = ? or t2.abbreviation is null)
                    and lower(ta.course) = lower(?)
                    and ta.date = current_date
                    and ta.check_out_time is null
                    ";
        $message = ['error' => 'Aún no ha marcado su entrada.', 'success' => 'Ya marcado su entrada, entonces, puede marcar su salida.'];
        $parameters = ['types' => 'isss', 'values' => [$teacher_id, $classroom, $turn, $course]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function get_attendances_of_teacher(int $teacher_id, string $classroom, $turn, string $course): array
    {
        $query = "
                    select ta.id as 'id', 
                           ta.classroom as 'classroom', 
                           t2.abbreviation as 'turn', 
                           ta.course as 'course', 
                           ta.date as 'date', 
                           ta.check_in_time as 'check in time', 
                           ta.check_out_time as 'check out time' 
                    from teacher_attendances ta 
                        join teachers t on ta.teacher_id = t.id
                        left join turns t2 on ta.turn_id = t2.id
                    where t.id = ?
                    and ta.classroom = ?
                    and (t2.abbreviation = ? or t2.abbreviation is null)
                    and lower(ta.course) = lower(?)
                    and ta.date = current_date
                    order by ta.check_in_time
                    ";
        $message = ['error' => 'Aún no ha registrada entrada y salida.', 'success' => 'Se han recuperado los registros de entrada y salida.'];
        $parameters = ['types' => 'isss', 'values' => [$teacher_id, $classroom, $turn, $course]];
        //$parameters = ['types' => 'isss', 'values' => []];                                                                                                                                                                                                                                        ', 'values' => [$teacher_id, $classroom, $turn, $course]];
        return $this->retrieve_records($query, $message, $parameters);
    }

    function register_check_in_time_of_teacher(int $teacher_id, string $classroom, $turn, string $course): array
    {
        $query = "
                   insert into teacher_attendances(classroom, turn_id, course, teacher_id, date, check_in_time, check_out_time) values (?, (
                       select t.id from turns t where (t.abbreviation = ?) 
                   ), ?, ?, current_date, current_time, null)
                    ";
        $message = ['error' => 'No se ha podido registrar la entrada.', 'success' => 'Se ha registrado su entrada.'];
        $parameters = ['types' => 'sssi', 'values' => [$classroom, $turn, $course, $teacher_id]];
        return $this->alter_record($query, $parameters, $message);
    }

    function register_check_out_time_of_teacher(int $teacher_id, string $classroom, $turn, string $course): array
    {
        $result = $this->check_in_time_of_teacher_exists($teacher_id, $classroom, $turn, $course);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['exists']) {
            return $result;
        }
        $result = $this->get_attendances_of_teacher($teacher_id, $classroom, $turn, $course);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $last_id = $result['records'][$result['count'] - 1]['id'];
        $query = "
                   update teacher_attendances ta
                   set ta.check_out_time = current_time
                    where ta.id = ?
                    ";
        $message = ['error' => 'No se ha podido registrar la salida.', 'success' => 'Se ha registrado su salida.'];
        $parameters = ['types' => 'i', 'values' => [$last_id]];
        return $this->alter_record($query, $parameters, $message);
    }

    function register_attendances_of_group(int $teacher_id, string $classroom, $turn, string $course, array $attended_by_student_id): array
    {
        $updated_count = 0;
        $created_count = 0;
        $not_modified_count = 0;
        foreach ($attended_by_student_id as $item) {
            $result = $this->check_if_student_has_attended($classroom, $turn, $course, intval($item['student id']));
            if (!$result['success']) {
                continue;
            }
            if (!$result['exists']) {
                $query = "insert into student_attendances (classroom, turn_id, course, student_id, teacher_id, attended, taken_at) values (?, (
                       select t.id from turns t where (t.abbreviation = ?) 
                   ), ?, ?, ?, ?, current_date)";
                $message = ['error' => 'No se pudo registrar la asistencia del estudiante', 'success' => 'Se ha registrado la asistencia del estudiante.'];
                $parameters = ['types' => 'sssiii', 'values' => [$classroom, $turn, $course, $item['student id'], $teacher_id, $item['attended']]];
                $result = $this->alter_record($query, $parameters, $message);
                if (!$result['success']) {
                    continue;
                }
                if (!$result['created']) {
                    continue;
                }
                $created_count++;
                continue;
            }
            $result = $this->get_attendance_of_student($classroom, $turn, $course, $item['student id']);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $id = $result['attendance']['id'];
            $query = "update student_attendances set attended = ? where id = ?";
            $message = ['error' => 'No se pudo actualizar la asistencia del estudiante', 'success' => 'Se ha actualizado la asistencia del estudiante.'];
            $parameters = ['types' => 'ii', 'values' => [$item['attended'], $id]];
            $result = $this->alter_record($query, $parameters, $message);
            if (!$result['success']) {
                continue;
            }
            if (!$result['created']) {
                $not_modified_count++;
                continue;
            }
            $updated_count++;
        }
        $count = $not_modified_count + $updated_count+ $created_count;
        $total = count($attended_by_student_id);
        return [
            'success' => $count > 0,
            'message' => $created_count > 0 ? "Se ha registrado las asistencias" : "Se ha actualizado las asistencias",
            'count' => $count,
            'total' => $total,
            'updated' => $updated_count,
            'created' => $created_count,
            'not modified' => $not_modified_count,
        ];
    }

    function get_attendances_of_group(string $classroom, $turn, string $course): array
    {
        $query = "
                    select s2.id                                                                    as 'id',
                           s2.code                                                                  as 'code',
                           concat(s2.paternal_surname, ' ', s2.maternal_surname, ' ', s2.firstname) as 'full name',
                           sa.attended                                                              as 'attended'
                    from `groups` g
                             left join turns t on g.turn_id = t.id
                             join teachers t2 on g.teacher_id = t2.id
                             join sections_by_group sbg on g.id = sbg.group_id
                             join sections s on sbg.section_id = s.id
                             join courses c on s.course_id = c.id
                             left join enrollments e on s.id = e.section_id
                             left join students s2 on e.student_id = s2.id
                            left join student_attendances sa on s2.id = sa.student_id
                    where g.classroom = ?
                      and (t.abbreviation = ? or t.abbreviation is null)
                      and lower(c.name) = lower(?)
                      and s2.id is not null 
                    order by concat(s2.paternal_surname, ' ', s2.maternal_surname, ' ', s2.firstname)
        ";
        $message = ['error' => "No hay registros de asistencias.", 'success' => "Se han encontrado registros de asistencias."];
        $parameters = ['types' => 'sss', 'values' => [$classroom, $turn, $course]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $marked = count(
            array_filter($result['records'], function (array $student) {
                return $student['attended'] != null;
            })
        );
        return [
            'success' => $result['success'],
            'found' => $result['found'],
            'message' => $result['message'],
            'marked' => $marked,
            'total' => $result['count'],
            'records' => $result['records']
        ];
    }

    function check_if_student_has_attended(string $classroom, $turn, string $course, int $student_id): array
    {
        $query = "
            select sa.id as 'id' from student_attendances sa
                     join teachers t2 on sa.teacher_id = t2.id
                     left join turns t on sa.turn_id = t.id
            where sa.classroom = ?
            and (t.abbreviation = ? or t.abbreviation is null)
            and lower(sa.course) = lower(?)
              and sa.student_id = ?
            and sa.taken_at = current_date
        ";
        $message = ['error' => "No hay registros de asistencias.", 'success' => "Se han encontrado registros de asistencias."];
        $parameters = ['types' => 'sssi', 'values' => [$classroom, $turn, $course, $student_id]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function get_attendance_of_student(string $classroom, $turn, string $course, int $student_id): array
    {
        $query = "
            select sa.id as 'id', 
                   sa.classroom as 'classroom', 
                   t.abbreviation as 'turn', 
                   sa.course, 
                   s.code as 'student code', 
                   concat(s.paternal_surname, ' ', s.maternal_surname, ' ', s.firstname) as 'student',
                   sa.attended as 'attended'
            from student_attendances sa
                join teachers t2 on sa.teacher_id = t2.id
                left join turns t on sa.turn_id = t.id
                join students s on sa.student_id = s.id
            where sa.classroom = ?
            and (t.abbreviation = ? or t.abbreviation is null)
            and lower(sa.course) = lower(?)
            and s.id = ?
            and sa.taken_at = current_date
        ";
        $message = ['error' => "No hay registros de asistencias.", 'success' => "Se han encontrado registros de asistencias."];
        $parameters = ['types' => 'sssi', 'values' => [$classroom, $turn, $course, $student_id]];

        $result = $this->retrieve_records($query, $message, $parameters);
        $result['attendance'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}