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
        return $this->insert($query, $parameters, $message);
    }

    function read(string $dni): array
    {
        $query = "select * from teachers where dni=?";
        $parameters = [
            'types' => "s",
            'values' => [$dni]
        ];
        $message = [
            'error' => "El profesor con dni $dni no existe.",
            'success' => "Se ha encontrado un profesor con dni $dni."
        ];
        $result = $this->select($query, $message, $parameters);
        $result['teacher'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}