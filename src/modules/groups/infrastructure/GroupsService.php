<?php

namespace modules\groups\infrastructure;

use modules\groups\application\CreateGroup;
use modules\groups\application\GetGroup;
use modules\groups\application\GetGroupById;
use modules\groups\application\GetLinkedSectionToGroup;
use modules\groups\application\GetStudentsOfGroupById;
use modules\groups\application\LinkSectionToGroup;

final class GroupsService
{
    private $repository;

    public function __construct()
    {
        $this->repository = new GroupsMySqlRepository();
    }

    public function create(string $classroom, $turn_id, string $course_name, int $teacher_id, int $section_id): array
    {
        $handler = new CreateGroup($this->repository);
        return $handler($classroom, $turn_id, $course_name, $teacher_id, $section_id);
    }

    public function find(string $classroom, $turn_id, string $course_name): array
    {
        $handler = new GetGroup($this->repository);
        return $handler($classroom, $turn_id, $course_name);
    }

    public function find_by_id(int $group_id): array
    {
        $handler = new GetGroupById($this->repository);
        return $handler($group_id);
    }

    public function link(int $section_id, int $group_id): array
    {
        $handler = new LinkSectionToGroup($this->repository);
        return $handler($section_id, $group_id);
    }

    public function read_link(int $section_id, int $group_id): array
    {
        $handler = new GetLinkedSectionToGroup($this->repository);
        return $handler($section_id, $group_id);
    }

    public function get_students(int $group_id) : array{
        $handler = new GetStudentsOfGroupById($this->repository);
        return $handler($group_id);
    }
}