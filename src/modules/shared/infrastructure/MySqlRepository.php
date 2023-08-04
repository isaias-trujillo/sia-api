<?php

namespace modules\shared\infrastructure;

use Exception;
use mysqli;

abstract class MySqlRepository
{
    private $hostname;
    private $username;
    private $password;
    private $database;
    private $port;

    public function __construct(
        string $hostname = 'localhost',
        string $username = 'root',
        string $password = '',
        string $database = 'sia',
        string $port = '3306')
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }

    private function connect(): array
    {
        try {
            return [
                'connected' => true,
                'connection' => new mysqli($this->hostname, $this->username, $this->password, $this->database, $this->port)
            ];
        } catch (Exception $exception) {
            return [
                'connected' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    protected function query(callable $callable): array
    {
        $server = $this->connect();
        if (!$server['connected']) {
            return [
                'success' => false,
                'message' => $server['message']
            ];
        }
        try {
            return call_user_func_array($callable, [$server['connection']]);
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    protected function insert(string $query, array $parameters, array $message, int &$affected_rows = 0): array
    {
        $server = $this->connect();
        if (!$server['connected']) {
            return ['success' => false, 'created' => false, 'message' => $server['message']];
        }
        try {
            $connection = $server['connection'];
            $statement = $connection->prepare($query);
            $statement->bind_param($parameters['types'], ...$parameters['values']);
            $statement->execute();
            $affected_rows = $connection->affected_rows;
            if ($affected_rows < 1) {
                return ['success' => true, 'created' => false, 'message' => $message['error']];
            }
            return ['success' => true, 'created' => true, 'message' => $message['success'], 'id' => $connection->insert_id];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    protected function check_if_exists(string $query, array $message, $parameters = null): array
    {
        $result = $this->select($query, $message, $parameters);
        return [
            'success' => $result['success'],
            'message' => $result['message'],
            'exists' => $result['found'],
        ];
    }

    protected function select(string $query, array $message, array $parameters = [], &$count = 0): array
    {
        $server = $this->connect();
        if (!$server['connected']) {
            return ['success' => false, 'created' => false, 'message' => $server['message']];
        }
        try {
            $connection = $server['connection'];
            $statement = $connection->prepare($query);
            if (isset($parameters['types']) && isset($parameters['values'])) {
                $statement->bind_param($parameters['types'], ...$parameters['values']);
            }
            $statement->execute();
            $result = $statement->get_result();
            if (!$result) {
                return ['success' => true, 'found' => false, 'message' => $message['error']];
            }
            $records = $result->fetch_all(MYSQLI_ASSOC);
            $count = count($records);
            return ['success' => true, 'found' => $count > 0, 'message' =>$count > 0 ? $message['success'] : $message['error'], 'count' => $count, 'records' => $records];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
