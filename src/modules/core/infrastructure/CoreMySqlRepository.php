<?php

namespace modules\core\infrastructure;

use modules\core\application\StandardizeCareers;
use modules\core\application\StandardizeTeachers;
use modules\core\domain\Repository;
use modules\shared\infrastructure\MySqlRepository;

final class CoreMySqlRepository extends MySqlRepository implements Repository
{
    function standardize(array $records): array
    {
        $total = count($records);
        $careers = StandardizeCareers::run($records);
        $teachers = StandardizeTeachers::run($records);

        $overview = [
            'careers' => $careers,
            'teachers' => $teachers
        ];

        $controller = function () use ($records, $overview,$total) {
            return [
                'success' => true,
                'message' => "Se han procesado $total registros.",
                'overview' => $overview,
                'records' => $records
            ];
        };
        return $this->query($controller);
    }
}