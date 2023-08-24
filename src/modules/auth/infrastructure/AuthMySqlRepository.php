<?php

namespace modules\auth\infrastructure;

use modules\auth\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class AuthMySqlRepository extends MySqlRepository implements Repository
{

    function exists(string $dni): array
    {
        $query = "select id from teachers where dni=?";
        $message = [
            'error' => "El docente con dni $dni no existe.",
            'success' => "Se ha encontrado al docente con dni $dni."
        ];
        $parameters = ['types' => 's', 'values' => [$dni]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function find(string $dni): array
    {
        $query = "select * from teachers where dni=?";
        $message = [
            'error' => "DNI incorrecto.",
            'success' => "Se ha encontrado al docente con dni $dni."
        ];
        $parameters = ['types' => 's', 'values' => [$dni]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['teacher'] = $result['found'] ? $result['records'][0] : null;
        unset($result['count'], $result['records']);
        return $result;
    }
}