# Phox
A lightweight, powerful, minimalistic, easy to understand MVC framework for PHP.
This framework is very similar to Laravel, but much more lightweight, and meant for apps 
that are a little to big to not use a framework,  but too small to use something like Laravel.
### Models not supported yet, just controllers and views.

## Basic overview
`public/index.php` is the entery point for the framework. All requests are sent here.  
Routes are registered `public/index.php`, the router takes care of assigning different actions for each route.  
Controllers are placed in `Phox/Controllers/`.   
Models are places in `Phox/Models` (Not implemented yet).  
Views are stored in `views/`. Views are rendered with a template engine yet, just pure PHP.  
Data can be 'passed' into views and accessed using `$GLOBALS['var_name']`, where `var_name` is the name of the variable that was 'passed' into the view.
You may use `DB::query()` to make secure queries to the DB after you call `DB::connect()`

### Register routes in `index.php`:
```php
<?php

require_once __DIR__ . '/../Phox/ini.php';
require_once __DIR__ . '/../autoload.php';

use Phox\Router\Router;

// Use Router::get for retreiving pages and data.
Router::get('/hello', function () {
  return 'Hello, Phox!';
});

// Use Router::post() for posting data
Router::post('/post-name', function () {
  $name = $_POST['name'];
  
  // do something with $name...
});
```

### Use the `View::render()` function to load views:

```php
// public/index.php

use Phox\View\View;

Router::get('', function () {
  View::render('home.php'); // load views/home.php
});
```

```html
<!-- views/home.php -->

<h1>Welcome to the home page!</h1>
```

### Use URI segments to pass in parameters (EXPERIMENTAL)
```php
// public/index.php


// When '/article/1' is visited in the browser, this will display 'Welcome to article 1', 
// When 'article/2' is visitid, this will display 'Welcome to article 2'
Router::get('/article/{id}', function ($id) {
  return "Welcome to article $id";
});



```

### Use controllers instead of closures in routes:
```php
// Phox/Controllers/HomeController.php
<?php

namespace Phox\Controllers;

class HomeController {
  public static function home () {
    return View::render('home.php');
  }
  
  public static function displayFunnyMessage () {
    return 'Eat that bagel or the bagel will eat you.';
  }
  
};

```

```php
// public/index.php

use Phox\Controllers\HomeController;

Router::get('/home', [HomeController::class, 'home']);
Router::get('/funny', [HomeController::class, 'displayFunnyMessage']);
```

### Pass in data to views (EXPERIMENTAL):
```php
<?php

namespace Phox\Controllers;

class ExampleController {
  public static function home () {
    return View::render('home.php', ['name' => 'john', 'height' => '5`6']);
  }
};

```

Then you can access the variables from the view with the `$GLOBALS` array:
```php

Hello, I'm <?php echo $GLOBALS['name'] ?>, my height is <?php $GLOBALS['height'] ?>.
```
The `$GLOBALS` variables that were set when calling `View::render()` are unset after the view is loaded.


### Use the DB class to make queries to the database securly.
```php
<?php
// index.php

use Phox\Database\DB;
// The DB file can be anywhere really, just not in the public/ folder!
$DBFilePath = '../database.php';
/**
 * Your DB file should look like this:
 * 
 * <?php
 * $dbPassword = 'database_password';
 * $dbUsername = 'database_user';
 * $dbName = 'database_name';
 * $dbHost = "localhost";
 * $dbType = "mysql";
 * $pdoOptions = array (PDO::ATTR_PERSISTENT => true);
 */

// DB::connect() only needs to be called once, usually this is done in the `index.php` file.

DB::connect($DBFilePath);
```
Once DB::connect is called, you can make queries from any file just by inlcuding the DB class:
```php
namespace Phox\Controllers;
use Phox\Database\DB;

class ExampleController {
  public static funciton databaseDemo () {
    return DB::query('SELECT * FROM users WHERE username = "john_doe";');
  }
};
```

#### More documentation to come!

