<?php

require_once __DIR__ . '/../Phox/ini.php';
require_once __DIR__ . '/../autoload.php';



use Phox\Router\Router;
use Phox\Database\DB;

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


// DB::connect('/home/tim/DBFiles/database.php');


Router::get('', function () {
    return 'Welcome to Phox!';
});


Router::route();
