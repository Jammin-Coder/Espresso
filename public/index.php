<?php

require_once __DIR__ . '/../Espresso/ini.php';
require_once __DIR__ . '/../autoload.php';



use Espresso\Router\Router;
use Espresso\View\View;
use Espresso\Database\DB;

use Espresso\Controllers\TestController;

View::$viewsDir = '../views';


// Database
/**
 * Your DB file should look like this:
 * 
 * <?php
 * $dbPassword = 'database_password';
 * $dbUsername = 'database_user';
 * $dbName = 'database_name';
 * $dbHost = "localhost";
 * $dbType = "mysql";
 * $pdoOptions = array (PDO::ATTR_PERSISTENT => true);
 */


DB::connect('/home/tim/DBFiles/database.php');


Router::get('', [TestController::class, 'index']);

// Router::get('/api', [TestController::class, 'api']);
Router::get('/api', function () {
    return 'API RESPONSE FROM FUNCTION';
});

Router::post('/post-api', [TestController::class, 'postAPI']);


Router::route();
