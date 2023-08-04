<?php

namespace courses\domain;

interface Repository
{
    function exists(string $code) : array;
    function save(Course $course) : array;
    function find(Course $course) : array;
}