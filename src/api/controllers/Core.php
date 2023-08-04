<?php

namespace api\controllers;

use api\core\Request;
use modules\core\application\Request as Record;
use modules\core\infrastructure\CoreService;

final class Core
{
    static function update(Request $request)
    {
        $service = new CoreService();
        $records = array_map(function (array $row){
            return new Record(
                $row['ape_paterno'],
                $row['ape_materno'],
                $row['nom_docente'],
                $row['DNI'],
                $row['aula_turno'],
                $row['cod_asignatura'],
                $row['des_asignatura'],
                $row['num_ciclo_ano_asignatura'],
                $row['cod_seccion'],
                $row['cod_escuela']
            );
        }, $request->body());

        $result = $service->save($records);

        echo json_encode($result);
    }
}