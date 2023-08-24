<?php

namespace modules\attendances\domain;

interface Repository
{
    function check_in_time_of_teacher_exists(int $teacher_id, string $classroom, $turn, string $course) : array;
    function get_attendances_of_teacher(int $teacher_id, string $classroom, $turn, string $course) : array;
    function register_check_in_time_of_teacher(int $teacher_id, string $classroom, $turn, string $course): array;

    function register_check_out_time_of_teacher(int $teacher_id, string $classroom, $turn, string $course): array;

    function check_if_student_has_attended(string $classroom, $turn, string $course, int $student_id): array;
    function get_attendance_of_student(string $classroom, $turn, string $course, int $student_id): array;

    function register_attendances_of_group(int $teacher_id, string $classroom, $turn, string $course, array $attended_by_student_id): array;
    function get_attendances_of_group(string $classroom, $turn, string $course): array;

}