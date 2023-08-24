<?php

namespace modules\courses\domain;

interface Repository
{
    function exists(Course $course) : array;
    function save(Course $course) : array;
    function find(Course $course) : array;
}