<?php

namespace modules\shared\domain;

abstract class Entity
{
    abstract function to_array() : array;
}