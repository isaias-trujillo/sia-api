<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE');
header('Access-Control-Allow-Headers: Content-Type,Access-Control-Allow-Headers,Authorization,X-Requested-With');

require_once __DIR__."/src/autoload.php";

use api\core\App;
use api\core\Endpoints;

$root = basename(__DIR__)."/api";
$app = new App($root);
Endpoints::load($app);	
$app();
