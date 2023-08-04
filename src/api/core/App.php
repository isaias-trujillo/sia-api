<?php

namespace api\core;

final class App
{
    private $root = "";
    private $routes = [];

    public function __construct(string $root = "/api")
    {
        $this->root = "/$root";
    }

    function add(Router $router)
    {
        $this->add_on("", $router);
    }

    function add_on(string $path, Router $router)
    {
        foreach ($router->routes as $route => $handlers) {
            $key = $this->root . $path . $route;
            $this->routes[$key] = $handlers;
        }
    }

    function __invoke()
    {
        $route_found = $this->get_request_info($handler, $parameters, $query, $body);
        if (!$route_found) {
            http_response_code(404);
            echo json_encode(["message" => "Route not found."]);
            return;
        }
        if (!$handler || !is_callable($handler)) {
            http_response_code(405);
            echo json_encode(["message" => "Method not allowed"]);
            return;
        }
        $request = new Request($parameters, $query, $body);
        call_user_func_array($handler, [$request]);
    }

    function get_path_tokens(string $path): array
    {
        $path = str_replace($this->root, "", $path);
        $pattern = "/\/:?\w+/";
        preg_match_all($pattern, $path, $tokens);
        return array_filter($tokens[0] ?? [], function (string $ref): bool {
            return $ref != "/";
        });
    }


    private function get_request_info(&$handler = null, &$parameters = [], &$query = null, &$body = null): bool
    {
        $url = parse_url($_SERVER['REQUEST_URI']);
        $path = $url['path'];
        $method = $_SERVER['REQUEST_METHOD'];

        $tokens = [
            'request' => $this->get_path_tokens($path)
        ];

        if (isset($url['query'])) {
            $query = [];
            foreach (explode("&", $url['query']) as $token) {
                list($key, $value) = explode("=", $token, 2);
                $query[$key] = $value;
            }
        }

        // takes raw data from the request 
        $json = file_get_contents('php://input');
        // Converts it into a PHP object 
        $body = json_decode($json, true);

        foreach ($this->routes as $route => $handlers) {
            $filtered_path = str_replace($this->root, "", $path);
            $filtered_route = str_replace($this->root, "", $route);
            if ($filtered_path == $filtered_route || "$filtered_path/" == $filtered_route) {
                $handler = $handlers[$method] ?? null;
                return true;
            }

            $tokens['route'] = $this->get_path_tokens($route);

            if (sizeof($tokens['request']) != sizeof($tokens['route'])) {
                continue;
            }

            $size = sizeof($tokens['route']);

            if ($size == 0) {
                continue;
            }

            for ($i = 0; $i < $size; $i++) {
                if ($tokens['route'][$i] == $tokens['request'][$i]) {
                    if ($i != $size - 1) {
                        continue;
                    }
                    $handler = $handlers[$method] ?? null;
                    return true;
                }
                $parameter = null;
                if (!preg_match("/:\w+/", $tokens['route'][$i], $parameter)) {
                    break;
                }
                if (!empty($parameter) && isset($parameter[0])) {
                    $key = str_replace(":", "", $parameter[0]);
                    $value = str_replace("/", "", $tokens['request'][$i]);
                    $parameters[$key] = $value;
                }
                if ($i != $size - 1) {
                    continue;
                }
                $handler = $handlers[$method] ?? null;
                return true;
            }
        }
        return false;
    }
}
