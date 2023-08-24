<?php

namespace modules\attendances\infrastructure;

use modules\attendances\application\GetAttendancesOfGroup;
use modules\attendances\application\GetAttendancesOfTeacher;
use modules\attendances\application\RegisterAttendanceOfTeacher;
use modules\attendances\application\RegisterAttendancesOfGroup;
use modules\groups\infrastructure\GroupsService;
use modules\teachers\infrastructure\TeacherService;

class AttendanceService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new AttendanceMySqlRepository();
    }

    public function get_attendance_of_group_by_id(int $group_id): array
    {
        $group_service = new GroupsService();
        $result = $group_service->find_by_id($group_id);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $group = $result['group'];
        $handler = new GetAttendancesOfGroup($this->repository);
        return $handler($group['classroom'], $group['turn'], $group['course']);
    }

    public function register_attendance_of_group_by_id(string $dni, int $group_id, array $attended_by_student_id): array
    {
        $group_service = new GroupsService();
        $result = $group_service->find_by_id($group_id);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $group = $result['group'];
        $teacher_service = new TeacherService();
        $result = $teacher_service->findByDNI($dni);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $teacher = $result['teacher'];
        $handler = new RegisterAttendancesOfGroup($this->repository);
        return $handler(intval($teacher['id']), $group['classroom'], $group['turn'], $group['course'], $attended_by_student_id);
    }

    public function get_attendance_of_teacher(string $teacher_dni, int $group_id): array
    {
        $group_service = new GroupsService();
        $result = $group_service->find_by_id($group_id);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $group = $result['group'];
        $teacher_service = new TeacherService();
        $result = $teacher_service->findByDNI($teacher_dni);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $teacher = $result['teacher'];
        $handler = new GetAttendancesOfTeacher($this->repository);
        return $handler(intval($teacher['id']), $group['classroom'], $group['turn'], $group['course']);
    }
    public function register_attendance_of_teacher(string $teacher_dni, int $group_id): array
    {
        $group_service = new GroupsService();
        $result = $group_service->find_by_id($group_id);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $group = $result['group'];
        $teacher_service = new TeacherService();
        $result = $teacher_service->findByDNI($teacher_dni);
        if (!$result['success']) {
            return $result;
        }
        if (!$result['found']) {
            return $result;
        }
        $teacher = $result['teacher'];
        $handler = new RegisterAttendanceOfTeacher($this->repository);
        return $handler(intval($teacher['id']), $group['classroom'], $group['turn'], $group['course']);
    }
}