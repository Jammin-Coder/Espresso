<?php

namespace Phox\Router;
use Phox\Auth\XCSRF;
use Phox\Router\UrlParser;

class Router
{
    public static array $routes;

    private static function handleCallbacks(string $resource, string $requestMethod, $callback)
    {   
        // This executes if the programmer is just using a plain callback function.
        if (gettype($callback) !== "array") {

             // Set the callback and request method for this route.
            self::$routes[$resource] = [
                "callback" => $callback,
                "method" => $requestMethod
            ];
            return;
        }
        

        // If $callback type is an array, then the programmer wants to use a controller class.
        $controller = $callback[0]; // Get the name of the controller
        $controllerMethod = $callback[1]; // Get the method of the controller the programmer wants to call
        
        // Set the callback, controller, and request method for this route.
        self::$routes[$resource] = [
            "callback" => $controllerMethod,
            "controller" => $controller,
            "method" => $requestMethod
        ];

        // If the length of the $callback array is more than 2, then the programmer is supplying
        // arguments to be passed into the method.
        // Extract the arguments and add them to self::$routes[$resource]["args"].
        if (count($callback) > 2) {
            self::$routes[$resource]["args"] = array_slice($callback, 2);
        }
    }

    
    public static function get(string $resource, $callback)
    {
        /**
         * Register a post route
         * @param $resource string: The URI you want to register.
         * @param $callback callable OR array [Controller::class, 'method', ? 'arg']
         * 
         * If a function is supplied to $callback, it will be executed.
         * If an array is supplied to $callback, the router will execute the method in the 1st array index (2nd item).
         * The method must belong to the supplied class in the 0th index (1st item).
         * If the selected method takes any arguments, then supply them in the indices after the method.
         * 
         * Examples
         * 
         * Router::get('/some-uri', function () {
         *  // Execute the code inside this function when this route is requested.
         * });
         * 
         * Executes the 'page()' method of the UserController class:
         * Router::get('/login', [UserController::class, 'page']);
         * 
         * Executes the 'sayHello' method of the UserController class and 
         * supplies 'username' to the 'sayHello' method parameters:
         * Router::get('/say-hello', [UserController::class, 'username']);
         * 
        */

        if ($_SERVER["REQUEST_METHOD"] !== "GET") return;
        
        self::handleCallbacks($resource, "GET", $callback);
        
        
    }

    public static function post(string $resource, $callback)
    {   
        /**
         * Register a post route
         * @param $resource string: The URI you want to register.
         * @param $callback callable OR array [Controller::class, 'method', ? 'arg']
         * 
         * If a function is supplied to $callback, it will be executed.
         * If an array is supplied to $callback, the router will execute the method in the 1st array index (2nd item).
         * The method must belong to the supplied class in the 0th index (1st item).
         * If the selected method takes any arguments, then supply them in the indices after the method.
         * 
         * Examples
         * 
         * Router::post('/some-uri', function () {
         *  // Execute the code inside this function when this route is requested.
         * });
         * 
         * Executes the 'login()' method of the UserController class:
         * Router::post('/login', [UserController::class, 'login']);
         * 
         * Executes the 'register' method of the UserController class and 
         * supplies 'username', 'email' and 'password' to the 'register($username, $email, $password)' method:
         * Router::post('/register', [UserController::class, 'register', 'username', 'email', 'password']);
         * 
        */

        // Don't do anything if the request method is POST!
        if ($_SERVER["REQUEST_METHOD"] !== "POST") return;
        
        self::handleCallbacks($resource, "POST", $callback);

        // Verify anti-CSRF token.
        if(!XCSRF::tokenIsOK($_POST[XCSRF::XCSRF_TOKEN])) {
            self::$routes[$resource] = [
                "callback" => function () {
                    echo "Token expired";
                }
            ];
        }
    }

    public static function executeCallback($routeData) {
        $callback = $routeData["callback"];
                    
        if (isset($routeData["args"])) {
            // Execute callback with arguments
            $returnData = call_user_func($callback, ...$routeData["args"]);

        } else {

            // Execute callback without arguments.
            $returnData = call_user_func($callback);
        }
        
        // Send data back in response
        if ($returnData) echo $returnData;
    }

    public static function executeControllerCallback($routeData) {
        // This code executes if there is a controller used for the route callback.
        $controller = $routeData["controller"];
        $method = $routeData["callback"];
        error_log('Controller method: ' . $method);
        
        // Execute a controller method
        if (isset($routeData["args"])) {
            // Execute controller callback with arguments
            $returnData = call_user_func(array($controller, $method), ...$routeData["args"]);
        } else {
            // Execute controller callback without arguments.
            $returnData = call_user_func(array($controller, $method));
        }
        
        // Send data back in response
        if ($returnData) echo $returnData;
    }

    public static function processRoute($route, $routeData) {
        // If the route matches, execute the callback.
        if ($route == $_SERVER['PATH_INFO']) {
            error_log($route);
            if (isset($routeData["callback"])) {
                
                // This code executes if there is a plain function set as the route callback
                if (!isset($routeData["controller"])) {
                    
                    self::executeCallback($routeData);
                    // Stop processing route.
                    return true;
                }

            }

            self::executeControllerCallback($routeData);

            // Stop processing route.
            return true;
        }

        // Resource was not found
        return false;
    }

    public static function route()
    {
        /**
         * Uses routing concepts from https://stackoverflow.com/a/20961274/17273033
         * 
         * When the programmer creates a route using Router::post or Router::get, routes will be registered 
         * in self::$routes. After the programmer registers all of the their routes, they will call this method.
         * It will itterate over all of the routes, and execute the callback function/method that the programmer
         * associated with the route.
         * 
         */

        // Iterate through the registered list of routes.
        foreach (self::$routes as $route => $routeData) {            
            $resourceFound = self::processRoute($route, $routeData);

            // Resource was found, stop processing
            if ($resourceFound) return;

            // Otherwise display page not found.
        }

        // This can only be reached if none of the routes matched the path.
        echo "<h1>Sorry! Page not found</h1>";
    }
}