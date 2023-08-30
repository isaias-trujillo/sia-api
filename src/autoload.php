<?php

function my_custom_autoloader($class_name)
{
    $file = __DIR__ . '/' . $class_name . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "File not found";
    }
}

// add a new autoloader by passing a callable into spl_autoload_register()
spl_autoload_register('my_custom_autoloader');
