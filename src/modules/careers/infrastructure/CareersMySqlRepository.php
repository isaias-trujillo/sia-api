<?php

namespace modules\careers\infrastructure;

use modules\careers\domain\Career;
use modules\careers\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

class CareersMySqlRepository extends MySqlRepository implements Repository
{
    function exists(int $order): array
    {
        $query = "select * from careers where `order`=?";
        $message = [
            'error' => "No existe la escuela profesional ($order).",
            'success' => "Se ha encontrado la escuela profesional ($order)."
        ];
        $parameters = ['types' => 'i', 'values' => [$order]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function create(Career $career): array
    {
        $order = $career->order();
        $result = $this->exists($order);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe la escuela profesional ($order)"];
        }
        $query = "insert into careers(name, abbreviation, `order`) values (?,?,?)";
        $parameters = [
            'types' => "sss",
            'values' => array_values($career->to_array())
        ];
        $message = [
            'error' => "No se pudo registrar la escuela profesional.",
            'success' => "Se ha registrado la escuela profesional ($order)."
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function read(int $order): array
    {
        $query = "select * from careers where `order`=?";
        $parameters = [
            'types' => "i",
            'values' => [$order]
        ];
        $message = [
            'error' => "La escuela profesional ($order) no existe.",
            'success' => "Se ha encontrado la escuela profesional ($order)."
        ];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['order'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}