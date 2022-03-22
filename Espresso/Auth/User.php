<?php


namespace Espresso\Auth;

use Espresso\Database\DB;
use Espresso\Auth\SessionVars;


class User
{   

    public static string $error = "";

    public static function genUID($length = 32)
    {
        return substr(str_replace(['+', '/', '='], '', base64_encode(random_bytes(32))), 0, $length);
    }

    public static function emailValid($email)
    {
        if (
            strlen($email) < 100 && 
            filter_var($email, FILTER_VALIDATE_EMAIL)
        ) {
            return true;
        }
        return false;
    }

    public static function usernameValid($username) {
        if (
            preg_match("/^[a-zA-Z0-9_]+$/", $username) && 
            strlen($username) < 100
        ) {
            return true;
        }

        return false;
    }

    public static function passwordValid($password)
    {
        if (strlen($password) >= 8) return true;
        return false;
    }

    public static function passwordMatchesUserInfo($password, $userRow)
    {
        if (password_verify($password, $userRow["hashed_password"])) {
            return true;
        }

        return false;
    }

    private static function getUserRow($identifier)
    {
        return DB::query(
            "SELECT * FROM users WHERE email = (:identifier) OR username = (:identifier) OR uuid = (:identifier)",
            ["identifier" => $identifier]
        );

    }

    public static function login($identifier, $password)
    {
        $userRow = self::getUserRow($identifier);

        if (!$userRow || !self::passwordMatchesUserInfo($password, $userRow)) {
            // User doesn't exist
            self::$error = "Those credentials don't match our records.";
            return;
        }

        session_start();
        session_regenerate_id(true);
        $_SESSION[SessionVars::UUID] = $userRow["uuid"];
        $_SESSION[SessionVars::USERNAME] = $userRow["username"];
    }

    public static function logout()
    {
        // Thanks php.net: https://www.php.net/manual/en/function.session-destroy.php
        
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();
    }

    

    public static function create($username, $email, $password)
    {
        $existingUser = self::getUserRow($email);
        if ($existingUser) {
            self::$error = "User already exists.";
            return;
        }

        DB::query(
            "INSERT INTO users (username, email, hashed_password, uuid) 
            VALUES (:username, :email, :hashed_password, :uuid);",
            [
                "username" => $username,
                "email" => $email,
                "hashed_password" => password_hash($password, PASSWORD_BCRYPT),
                "uuid" => self::genUID()
            ]
        );

        echo "<p>User created</p>";
    }
}