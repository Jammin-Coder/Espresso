<?php


namespace Espresso\Controllers;
use Espresso\Database\DB;
use Espresso\View\View;

class TestController {
    public static function index() {
        return View::render('home.php');
    }

    public static function api() {
        return 'API RESPONSE';
    }

    public static function postAPI() {
        error_log('POST API HIT');
        error_log(file_get_contents('php://input'));

        return 'POST API RESPONSE';
    }
};