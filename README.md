
# RawRouter - A Simple Routing Service for MVC PHP Applications

## Package Features

- Simple to use router
- MVC style routing

## Installation

### Composer
RawRouter is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-router).

Add `"rawphp/raw-router": "0.1.1"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-router": "0.1.1"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-router "0.1.1"
```

### Tarball
Alternatively, just copy the contents of the RawRouter folder into somewhere that's in your PHP `include_path` setting. If you don't speak git or just want a tarball, click the 'zip' button at the top of the page in GitHub.

## Basic Usage

```php
<?php

use RawPHP\RawRouter\Router;

// configuration
$config = array(
    'default_controller' => 'home',                             // the controller that will handle requests if the requested controller is not found
    'default_action'     => 'index',                            // the default action (method) to call if the requested action is not found
    'namemspace'         => 'RawPHP\\RawRouter\\Controllers\\', // the controllers namespace, leave empty if namespaces are not used
);

// get new router instance
$router = new Router( );

// initialise router
$router->init( $config );

// example request
// http://example.com/users/get/1

$route = 'users/get';                                           // the route must be in one of the following formats [ controller, controller/action, controller/action/param/param/... ]
$params = array( 1 );                                           // array of values to be passed to the action method in the correct order

// create controller
$controller = $router->createController( $route, $params );

// run controller action
$controller->run( );
```

## License
This package is licensed under the [MIT](https://github.com/rawphp/RawRouter/blob/master/LICENSE). Read LICENSE for information on the software availability and distribution.

## Contributing

Please submit bug reports, suggestions and pull requests to the [GitHub issue tracker](https://github.com/rawphp/RawRouter/issues).

## Changelog

#### 14-09-2014
- Implemented the hook system.
- Renamed RawController -> Controller.

#### 12-09-2014
- Changed router constructor to take a configuration array rather than individual parameters.

#### 11-09-2014
- Initial Code Commit
