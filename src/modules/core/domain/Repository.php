<?php

namespace modules\core\domain;

interface Repository
{
    function standardize_course_data(array $records) : array;
    function standardize_enrollment_data(array $records) : array;
}