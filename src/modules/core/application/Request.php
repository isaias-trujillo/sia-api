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
    private $section;
    private $career;

    public function __construct($paternal_surname = null, $maternal_surname = null, $firstname = null, $dni = null, $classroom_and_turn = null, $course_code = null, $course = null, $cycle = null, $section = null, $career = null)
    {
        $this->paternal_surname = $paternal_surname;
        $this->maternal_surname = $maternal_surname;
        $this->firstname = $firstname;
        $this->dni = $dni;
        $this->classroom_and_turn = $classroom_and_turn;
        $this->course_code = $course_code;
        $this->course = $course;
        $this->cycle = $cycle;
        $this->section = $section;
        $this->career = $career;
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

    public function cycle()
    {
        return $this->cycle;
    }

    public function section()
    {
        return $this->section;
    }

    public function career()
    {
        return $this->career;
    }
    public function set_career($career)
    {
        $this->career = $career;
    }
}