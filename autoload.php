<?php


spl_autoload_register('autoloader');

function autoloader($className)
{
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    require $file;
}