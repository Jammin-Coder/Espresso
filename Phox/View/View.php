<?php

namespace Phox\View;

class View
{
    // Assuming the index.php file is hosted in `public/`, 
    // then the views folder would be in public/../views
    public static $viewsDir = "../views";
    public static function render($viewPath, $vars = null)
    {
        // Set any variables from the $vars array, and store them in $GLOBALS
        // to be accessed by the view
        if ($vars) {
            foreach($vars as $varname => $value) {
                $GLOBALS[$varname] = $value;
            }
        }
        
        // Include the selected view
        include self::$viewsDir . "/$viewPath";
        
        // Destroy any set global variables after they were used.
        if ($vars) {
            foreach($vars as $varname => $value) {
                unset($GLOBALS[$varname]);
            }
        }
    }
}