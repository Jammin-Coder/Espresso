<?php

require_once __DIR__ . '/../Espresso/ini.php';
require_once __DIR__ . '/../autoload.php';



use Espresso\Router\Router;
use Espresso\View\View;
use Espresso\Database\DB;

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


DB::connect('/path/to/DBFile.php');


Router::get('', function () {
    echo '<h1>It works!</h1>';
});


Router::route();
