<?php

require_once __DIR__ . '/../config/ini.php';
require_once __DIR__ . '/../vendor/autoload.php';



use Phox\Router\Router;
use Phox\Database\DB;


DB::connect('../config/database.php');


Router::get('', function () {
    return 'Welcome to Phox!';
});


Router::route();
