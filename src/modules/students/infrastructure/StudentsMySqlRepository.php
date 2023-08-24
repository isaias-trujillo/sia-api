<?php

namespace modules\students\infrastructure;

use modules\sections\infrastructure\SectionsService;
use modules\shared\infrastructure\MySqlRepository;
use modules\students\domain\Repository;

class StudentsMySqlRepository extends MySqlRepository implements Repository
{

    function exists(string $student_code): array
    {
        $query = "select id from students where code=?";
        $message = [
            'error' => "El estudiantes con código '$student_code' no existe.",
            'success' => "Se ha encontrado al estudiantes con código '$student_code'."
        ];
        $parameters = ['types' => 's', 'values' => [$student_code]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function save(array $student): array
    {
        $student_code = $student['student code'];
        $result = $this->exists($student_code);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe un estudiante con el código $student_code."];
        }
        $query = "insert into students (code, paternal_surname, maternal_surname, firstname, email) values (?, ?, ?, ?, ?)";
        $parameters = [
            'types' => "sssss",
            'values' => [
                $student['student code'],
                $student['paternal surname'],
                $student['maternal surname'],
                $student['firstname'],
                $student['email'],
            ]
        ];
        $message = [
            'error' => "No se pudo registrar al estudiante.",
            'success' => "Se ha registrado al estudiante con código '$student_code'."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function find(string $student_code): array
    {
        $query = "select * from students where code=?";
        $message = [
            'error' => "El estudiantes con código '$student_code' no existe.",
            'success' => "Se ha encontrado al estudiantes con código '$student_code'."
        ];
        $parameters = ['types' => 's', 'values' => [$student_code]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['student'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }

    function is_enrolled(string $student_code, string $course_code, int $section): array
    {
        $query = "select * from enrollments join students s2 on enrollments.student_id = s2.id join sections s on s.id = enrollments.section_id join courses c on c.id = s.course_id where s2.code = ? and c.code = ? and s.number = ?";
        $message = [
            'error' => "El estudiante con código '$student_code' no está matriculador en el curso con código '$course_code' de la sección $section",
            'success' => "El estudiante con código '$student_code' está matriculador en el curso con código '$course_code' de la sección $section"
        ];
        $parameters = ['types' => 'ssi', 'values' => [$student_code, $course_code, $section]];
        $result = $this->check_if_exists($query, $message, $parameters);
        $result['enrolled'] = $result['exists'];
        unset($result['exists']);
        return $result;
    }

    function enroll(string $student_code, string $course_code, int $section): array
    {
        $result = $this->find($student_code);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if (!$result['found']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        $student_id = $result['student']['id'];
        $section_service = new SectionsService();
        $result = $section_service->find_by_course_code_and_section_number($course_code, $section);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if (!$result['found']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        $section_id = $result['section']['id'];
        $result = $this->is_enrolled($student_code, $course_code, $section);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['enrolled']) {
            return ['success' => true, 'created' => false, 'message' => "Ya está matriculado el estudiante con código '$student_code' en el curso con código '$course_code' de la sección $section"];
        }
        $query = "insert into enrollments (section_id, student_id) values (?, ?)";
        $parameters = [
            'types' => "ii",
            'values' => [$section_id, $student_id]
        ];
        $message = [
            'error' => "No se pudo registrar al estudiante.",
            'success' => "Se ha registrado al estudiante con código '$student_code'."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function find_enroll(string $student_code, string $course_code, int $section): array
    {
        $query = "select * from enrollments e 
                    join students s on s.id = e.student_id 
                    join courses c on s.code = c.code 
                    join sections s2 on e.section_id = s2.id
                where s.code = ? and 
                      c.code = ? and 
                      s2.number = ?
                  ";
        $message = [
            'error' => "El estudiante con código '$student_code' no está matriculador en el curso con código '$course_code' de la sección $section",
            'success' => "El estudiante con código '$student_code' está matriculador en el curso con código '$course_code' de la sección $section"
        ];
        $parameters = ['types' => 'ssi', 'values' => [$student_code, $course_code, $section]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['enrollment'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}