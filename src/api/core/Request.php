<?php

namespace api\core;

final class Request
{
    private $parameters;
    private $query;
    private $body;

    public function __construct($parameters, $query, $body)
    {
        $this->parameters = $parameters;
        $this->query = $query;
        $this->body = $body;
    }

    public function parameters()
    {
        return $this->parameters;
    }

    public function query()
    {
        return $this->query;
    }

    public function body()
    {
        return $this->body;
    }
}