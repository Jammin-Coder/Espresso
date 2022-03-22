<?php

namespace Espresso\View;

class View
{
    public static $viewsDir = "Views";
    public static function render($viewPath)
    {
        include self::$viewsDir . "/$viewPath";
    }
}