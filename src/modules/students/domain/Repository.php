<?php

namespace modules\students\domain;

interface Repository
{
    function exists(string $student_code): array;
    function save(array $student): array;
    function find(string $student_code): array;
    function is_enrolled(string $student_code, string $course_code, int $section) : array;

    function enroll(string $student_code, string $course_code, int $section) : array;

    function find_enroll(string $student_code, string $course_code, int $section) : array;
}
