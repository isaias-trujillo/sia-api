<?php

namespace api\controllers;

use api\core\Request;
use modules\auth\infrastructure\AuthService;

final class Auth
{
    private function __construct()
    {
    }

    public static function login(Request $request)  {
        $body = $request->body();
        if (!isset($body['dni'])) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay DNI.'
            ]);
            return;
        }
        $dni = $body['dni'];
        $service = new AuthService();
        $result = $service->find($dni);
        if (!$result['success']) {
            echo json_encode($result);
            return;
        }
        if (!$result['found']) {
            echo json_encode($result);
            return;
        }
        $result['teacher'] = [
          'id' => $result['teacher']['id'],
          'full name' => implode(' ', [$result['teacher']['paternal_surname'], $result['teacher']['maternal_surname'], $result['teacher']['firstname']]),
          'dni' => $result['teacher']['dni'],
          'code' => $result['teacher']['code'],
        ];
         echo json_encode($result);
    }
}