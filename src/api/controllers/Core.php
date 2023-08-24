<?php

namespace api\controllers;

use api\core\Request;
use modules\core\infrastructure\CoreService;

final class Core
{
    static function update(Request $request)
    {
        $courses = $request->body()['courses'] ?? null;
        $success = false;
        $overview = [];
        $message = [];
        $service = new CoreService();
        if (!empty($courses)) {
            $records = array_map('\api\controllers\Core::parse_course_data_row', $courses);
            $result = $service->save_course_data($records);
            $success = $result['success'];
            $overview['courses'] = $result['overview'];
            $message['courses'] = $result['message'];
        }
        $enrollments = $request->body()['enrollments'] ?? null;
        if (!empty($enrollments)) {
            set_time_limit(60 * 20);

            $records = array_map('\api\controllers\Core::parse_enrollment_data_row', $enrollments);
            $result = $service->save_enrollments_data($records);
            $success = ($success and $result['success']);
            $overview['enrollment'] = $result['overview'];
            $message['enrollment'] = $result['message'];

        }
        echo json_encode([
            'success' => $success,
            'overview' => $overview,
            'message' => $message
        ]);
    }

    private static function parse_course_data_row(array $row): array
    {
        return [
            'course code' => self::remove_whitespaces($row['cod_asignatura']),
            'section' => self::remove_whitespaces($row['cod_seccion']),
            'student limit' => self::remove_whitespaces($row['can_tope_alumnos']),
            'career' => self::remove_whitespaces($row['cod_escuela']),
            'study plan' => self::remove_whitespaces($row['cod_plan']),
            'semester' => self::remove_whitespaces($row['cod_semestre']),
            'classroom and turn' => self::remove_whitespaces($row['aula_turno']),
            'cycle' => self::remove_whitespaces($row['num_ciclo_ano_asignatura']),
            'credits' => self::remove_whitespaces($row['num_creditaje']),
            'course' => self::remove_whitespaces($row['des_asignatura']),
            'paternal surname' => self::remove_whitespaces($row['ape_paterno']),
            'maternal surname' => self::remove_whitespaces($row['ape_materno']),
            'firstname' => self::remove_whitespaces($row['nom_docente']),
            'dni' => self::remove_whitespaces($row['DNI']),
        ];
    }

    private static function parse_enrollment_data_row(array $row): array
    {
        return [
            'student code' => self::remove_whitespaces($row['cod_alumno']),
            'paternal surname' => self::remove_whitespaces($row['ape_paterno']),
            'maternal surname' => self::remove_whitespaces($row['ape_materno']),
            'firstname' => self::remove_whitespaces($row['nom_alumno']),
            'email' => self::remove_whitespaces($row['coe_alumno']),
            'course code' => self::remove_whitespaces($row['cod_asignatura']),
            'section' => self::remove_whitespaces($row['cod_seccion']),
        ];
    }

    private static function remove_whitespaces($text): string
    {
        if (!$text){
            return $text;
        }
        $modified = preg_replace("/\s+/", ' ', (string)$text);
        return trim($modified);
    }
}