<?php

namespace Phox\View;

class View
{
    public static $viewsDir = "views";
    public static function render($viewPath)
    {
        include self::$viewsDir . "/$viewPath";
    }
}