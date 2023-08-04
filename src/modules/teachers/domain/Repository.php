<?php

namespace modules\teachers\domain;

interface Repository
{
    function exists(string $dni) : array;
    function create(Teacher $teacher) : array;
    function read(string $dni) : array;
}