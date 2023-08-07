<?php

namespace modules\sections\domain;

interface Repository
{
    function exists(int $number, int $course_id): array;
    function create(int $number, int $course_id, int $student_limit): array;
    function read(int $number, int $course_id): array;
}