<?php

namespace modules\groups\domain;

interface Repository
{
    function exists(string $classroom, $turn_id, string $course_name): array;

    function create(string $classroom, $turn_id, string $course_name, int $teacher_id, int $section_id): array;

    function read(string $classroom, $turn_id, string $course_name): array;

    function is_linked(int $section_id, int $group_id) : array;
    function link(int $section_id, int $group_id) : array;
    function read_link(int $section_id, int $group_id): array;
}