<?php

namespace api\core;
class Router
{
    public $routes = [];

    private function add_route(string $method, string $path, callable $handler)
    {
        $this->routes[$path][$method] = $handler;
    }

    function get(string $path, callable $handler)
    {
        $this->add_route("GET", $path, $handler);
    }

    function post(string $path, callable $handler)
    {
        $this->add_route("POST", $path, $handler);
    }

    function put(string $path, callable $handler)
    {
        $this->add_route("PUT", $path, $handler);
    }

    function delete(string $path, callable $handler)
    {
        $this->add_route("DELETE", $path, $handler);
    }
}
