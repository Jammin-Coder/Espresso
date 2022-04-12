<?php


namespace Phox\Controllers;
use Phox\View\View;

class TestController {
    public static function index() {
        return View::render('home.php', ['test_var' => 'VALUE', 'other_var' => 1]);
    }

    public static function api() {
        return 'API RESPONSE';
    }

    public static function postAPI() {
        return 'POST API RESPONSE';
    }
};