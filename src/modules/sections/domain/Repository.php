<?php

namespace modules\sections\domain;

interface Repository
{
    function exists(int $number, int $course_id): array;
    function create(int $number, int $course_id, int $student_limit): array;
    function read(int $number, int $course_id): array;
    function read_by_course_and_number_section(string $course_code, int $section): array;
}