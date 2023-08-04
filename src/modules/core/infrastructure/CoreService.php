<?php

namespace modules\core\infrastructure;

use modules\core\application\StandardizeAllAcademicData;

final class CoreService
{
    function save(array $records): array
    {
        $repository = new CoreMySqlRepository();
        $case = new StandardizeAllAcademicData($repository);
        return $case($records);
    }
}