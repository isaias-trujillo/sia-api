<?php

namespace modules\clock\domain;

interface Repository
{
    function get_time_info() : array;
}