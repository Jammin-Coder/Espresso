<?php

require_once __DIR__ . '/../Phox/ini.php';
require_once __DIR__ . '/../autoload.php';



use Phox\Router\Router;
use Phox\View\View;
use Phox\Database\DB;

use Phox\Controllers\TestController;

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
