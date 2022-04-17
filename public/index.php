<?php

require_once __DIR__ . '/../config/ini.php';
require_once __DIR__ . '/../vendor/autoload.php';



use Phox\Router\Router;
use Phox\Database\DB;
use Phox\View\View;

DB::connect('../config/database.php');


Router::get('', function () {
    return View::render('example.html', ['name' => 'Example name']);
});

Router::get('/name/{name}', function ($name) {
    return View::render('example.html', ['name' => $name]);
});


Router::route();
