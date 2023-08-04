<?php

require_once __DIR__."/src/autoload.php";

use api\core\App;
use api\core\Endpoints;

$root = basename(__DIR__)."/api";
$app = new App($root);
Endpoints::load($app);
$app();