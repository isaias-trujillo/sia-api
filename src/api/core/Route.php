<?php

namespace api\core;

abstract class Route
{
    abstract function load(App $app);
}