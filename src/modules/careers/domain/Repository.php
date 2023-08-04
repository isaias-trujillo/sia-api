<?php

namespace modules\careers\domain;

interface Repository
{
    function exists(int $order): array;

    function create(Career $career): array;

    function read(int $order): array;
}