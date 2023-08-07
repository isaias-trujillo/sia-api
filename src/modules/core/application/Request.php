<?php

namespace modules\core\application;

final class Request
{
    private $paternal_surname;
    private $maternal_surname;
    private $firstname;
    private $dni;
    private $classroom_and_turn;
    private $course_code;
    private $course;
    private $cycle;
    private $credits;
    private $section;
    public $study_plan;

    public $career;

    public function __construct($paternal_surname = null, $maternal_surname = null, $firstname = null, $dni = null, $classroom_and_turn = null, $course_code = null, $course = null, $cycle = null, $credits = null, $section = null, $study_plan = null, $career = null)
    {
        $this->paternal_surname = $this->remove_whitespaces($paternal_surname);
        $this->maternal_surname = $this->remove_whitespaces($maternal_surname);
        $this->firstname = $this->remove_whitespaces($firstname);
        $this->dni = $this->remove_whitespaces($dni);
        $this->classroom_and_turn = $this->remove_whitespaces($classroom_and_turn);
        $this->course_code = $this->remove_whitespaces($course_code);
        $this->course = $this->remove_whitespaces($course);
        $this->cycle = $this->remove_whitespaces($cycle);
        $this->credits = $this->remove_whitespaces($credits);
        $this->section = $this->remove_whitespaces($section);
        $this->study_plan = $this->remove_whitespaces($study_plan);
        $this->career = $this->remove_whitespaces($career);
    }

    private function remove_whitespaces(string $text): string
    {
        $modified= preg_replace("/\s+/", ' ', $text);
        return trim($modified);
    }

    public function paternal_surname()
    {
        return $this->paternal_surname;
    }

    public function maternal_surname()
    {
        return $this->maternal_surname;
    }

    public function firstname()
    {
        return $this->firstname;
    }

    public function dni()
    {
        return $this->dni;
    }

    public function classroom_and_turn()
    {
        return $this->classroom_and_turn;
    }

    public function course_code()
    {
        return $this->course_code;
    }

    public function course()
    {
        return $this->course;
    }

    public function credits(){
        return $this->credits;
    }

    public function cycle()
    {
        return $this->cycle;
    }

    public function section()
    {
        return $this->section;
    }
}