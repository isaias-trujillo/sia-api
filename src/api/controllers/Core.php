<?php

namespace api\controllers;

use api\core\Request;
use modules\core\infrastructure\CoreService;

final class Core
{
    static function update(Request $request)
    {
        $service = new CoreService();
        $records = array_map('\api\controllers\Core::parse_row', $request->body());
        $result = $service->save($records);
        echo json_encode($result);
    }

    private static function parse_row(array $row): array
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

    private static function remove_whitespaces(string $text): string
    {
        $modified= preg_replace("/\s+/", ' ', $text);
        return trim($modified);
    }
}