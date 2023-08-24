<?php

namespace modules\auth\domain;

interface Repository
{
    function exists(string $dni) : array;
    function find(string $dni) : array;
}