<?php

namespace modules\core\domain;

interface Repository
{
    function standardize(array $records) : array;
}