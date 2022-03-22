<?php

namespace Espresso\Auth;

class XCSRF
{
    const XCSRF_TOKEN = "xcsrf_token";

    public static function newToken() 
    {
        return bin2hex(random_bytes(32));
    }

    public static function setNewCSRFToken()
    {
        $_SESSION[self::XCSRF_TOKEN] = self::newToken();
    }

    public static function csrfInput()
    {
        self::setNewCSRFToken();
        echo "<input type='hidden' name='" . self::XCSRF_TOKEN . "' value='" . $_SESSION[self::XCSRF_TOKEN] . "'>";
    }

    public static function tokenIsOK($postedToken)
    {
        session_start();
        if ($_SESSION[self::XCSRF_TOKEN] === $postedToken){
            return true;
        }
        return false;
    }
}
