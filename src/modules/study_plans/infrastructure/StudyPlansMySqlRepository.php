<?php

namespace modules\study_plans\infrastructure;

use modules\shared\infrastructure\MySqlRepository;
use modules\study_plans\domain\Repository;
use modules\study_plans\domain\StudyPlan;

class StudyPlansMySqlRepository extends MySqlRepository implements Repository
{
    function exists(int $year, int $career_id): array
    {
        $query = "select sp.id as 'id', sp.name as 'name' from study_plans sp join careers c on c.id = sp.career_id where year=? and c.id=?";
        $message = [
            'error' => "El plan de estudios $year de la E.P. ($career_id) no existe.",
            'success' => "Se ha encontrado el plan de estudios $year de la E.P. ($career_id)."
        ];
        $parameters = ['types' => 'ii', 'values' => [$year, $career_id]];
        return $this->check_if_exists($query, $message, $parameters);
    }

    function create(StudyPlan $plan): array
    {
        $year = $plan->year();
        $career = $plan->career_id();
        $result = $this->exists($year, $career);
        if (!$result['success']) {
            return ['success' => false, 'created' => false, 'message' => $result['message']];
        }
        if ($result['exists']) {
            return ['success' => true, 'created' => false, 'message' => "Ya existe el plan de estudios $year de la $career."];
        }
        $query = "insert into study_plans(name, year, career_id) values (?, ?, ?)";
        $parameters = [
            'types' => "sss",
            'values' => array_values($plan->to_array())
        ];
        $message = [
            'error' => "No se pudo registrar el plan de estudios.",
            'success' => "Se ha registrado el plan de estudios $year de la $career"
        ];
        return $this->alter_record($query, $parameters, $message);
    }

    function read(int $year, int $career_id): array
    {
        $query = "select sp.id as 'id', sp.name as 'name' from study_plans sp join careers c on c.id = sp.career_id where year=? and c.id=?";
        $message = [
            'error' => "El plan de estudios $year de la E.P. ($career_id) no existe.",
            'success' => "Se ha encontrado el plan de estudios $year de la E.P. ($career_id)."
        ];
        $parameters = ['types' => 'ii', 'values' => [$year, $career_id]];
        $result = $this->retrieve_records($query, $message, $parameters);
        $result['study plan'] = $result['found'] ? $result['records'][0] : null;
        unset($result['records'], $result['count']);
        return $result;
    }
}