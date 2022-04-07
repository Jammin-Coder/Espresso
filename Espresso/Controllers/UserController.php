<?php

namespace Espresso\Controllers;
use Espresso\Auth\User;

class UserController
{
    public function register()
    {
        User::create($_POST["username"], $_POST["email"], $_POST["password"]);
        header("Location: /login");
    }

    public function login()
    {
        User::login($_POST["email"], $_POST["password"]);

        if (User::$error) {
            // error_log(User::$error);
            header("Location: /login?message=" . User::$error);
            return;
        }

        header("Location: /?message=logged_in");
    }

    public function logout()
    {
        User::logout();
        header("Location: /?message=logged_out");
    }
}