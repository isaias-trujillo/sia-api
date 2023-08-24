<?php

namespace modules\clock\infrastructure;

use modules\clock\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

final class ClockMySqlRepository extends MySqlRepository implements Repository
{
    function get_time_info(): array
    {
        $query = "select now()";
        $message = [
            'error' => 'No se pudo obtener la hora del servidor.',
            'success' => 'Se ha recuperado la hora del servidor.'
        ];
        $result = $this->retrieve_records($query, $message);
        if (!$result['success']) {
            return $result;
        }
        $result['datetime'] = $result['found'] ? $result['records'][0]['now()'] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}