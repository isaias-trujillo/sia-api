<?php

namespace modules\core\application;

use modules\careers\domain\Career;
use modules\careers\infrastructure\CareerService;

final class StandardizeCareers
{
    private static $careers = [
        '1' => [
            'name' => 'Contabilidad',
            'abbreviation' => 'C'
        ],
        '2' => [
            'name' => 'Gestión Tributaria',
            'abbreviation' => 'GT'
        ],
        '3' => [
            'name' => 'Auditoría Empresarial',
            'abbreviation' => 'A'
        ],
        '4' =>  [
            'name' => 'Presupuesto y Finanzas',
            'abbreviation' => 'PFP'
        ],
    ];

    public static function run(array $records): array
    {
        $career_order_list = array_values(array_unique(array_map(function (Request $request) {
            return $request->career();
        }, $records)));

        $careers = array_filter(array_map(function (string $order) use ($records) {
            $match = array_values(array_filter($records, function (Request $request) use ($order) {
                return $request->career() == $order;
            }))[0] ?? null;
            if (!$match) {
                return null;
            }
            $info = StandardizeCareers::$careers[$order] ?? ['name' => "Escuela profesional ($order})", 'abbreviation' => "E.P. ($order)"];
            return new Career($info['name'], $info['abbreviation'], intval($order));
        }, $career_order_list));

        $created = 0;
        $items = [];
        $service = new CareerService();
        foreach ($careers as $career) {
            $order = $career->order();
            $result = $service->create($career);
            if (!$result['success']) {
                continue;
            }
            if ($result['created']) {
                $items[$order] = $result['id'];
                $created += 1;
                continue;
            }
            $result = $service->find_by_order($order);
            if (!$result['success']) {
                continue;
            }
            if (!$result['found']) {
                continue;
            }
            $items[$order] = $result['order']['id'];
        }
        foreach ($records as $record){
            $id = $items[$record->career()];
            $record->set_career($id);
        }
        return ['total' => count($careers), 'created' => $created];
    }
}