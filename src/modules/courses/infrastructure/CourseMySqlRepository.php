<?php

namespace courses\infrastructure;

use courses\domain\Course;
use courses\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class CourseMySqlRepository extends MySqlRepository implements Repository
{
    function exists(string $code): array
    {
        $query = "select * from teachers where dni=?";
        $parameters = ['type' => 's', 'values' => [$code]];
        return $this->check_if_exists($query, $parameters);
    }

    function save(Course $course): array
    {
        $code = $course->code();
        $result = $this->exists($code);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe un curso con el código $code."];
        }
        $query = "insert into teachers(paternal_surname, maternal_surname, firstname, dni) values (?, ?, ?, ?)";
        $parameters = [
            'types' => "ss",
            'values' => array_values($course->to_array())
        ];
        $message = [
            'error' => "No se pudo registrar al profesor.",
            'success' => "Se ha registrado al profesor con dni $code."
        ];
        return $this->insert($query, $parameters, $message);
    }

    function find(Course $course): array
    {
        // TODO: Implement find() method.
    }
}