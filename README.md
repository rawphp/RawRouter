# RawRouter - A Simple Routing Service for MVC PHP Applications [![Build Status](https://travis-ci.org/rawphp/RawRouter.svg?branch=master)](https://travis-ci.org/rawphp/RawRouter)

[![Latest Stable Version](https://poser.pugx.org/rawphp/raw-router/v/stable.svg)](https://packagist.org/packages/rawphp/raw-router) [![Total Downloads](https://poser.pugx.org/rawphp/raw-router/downloads.svg)](https://packagist.org/packages/rawphp/raw-router) [![Latest Unstable Version](https://poser.pugx.org/rawphp/raw-router/v/unstable.svg)](https://packagist.org/packages/rawphp/raw-router) [![License](https://poser.pugx.org/rawphp/raw-router/license.svg)](https://packagist.org/packages/rawphp/raw-router)

## Package Features
- Simple to use router
- MVC style routing

## Installation

### Composer
RawRouter is available via [Composer/Packagist](https://packagist.org/packages/rawphp/raw-router).

Add `"rawphp/raw-router": "0.*@dev"` to the require block in your composer.json and then run `composer install`.

```json
{
        "require": {
            "rawphp/raw-router": "0.*@dev"
        }
}
```

You can also simply run the following from the command line:

```sh
composer require rawphp/raw-router "0.*@dev"
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

#### 18-09-2014
- Added `loadView()` method to Controller which can load html templates
- Added LEController that extends Controller and provides languages features. It can load different language translations required for use in html templates.

#### 17-09-2014
- Updated to work with the latest rawphp/rawbase package.
- Added extra hooks to Action.
- Removed the constructor added new `setAction( IAction )` method on Controller.

#### 14-09-2014
- Implemented the hook system.
- Renamed RawController -> Controller.

#### 12-09-2014
- Changed router constructor to take a configuration array rather than individual parameters.

#### 11-09-2014
- Initial Code Commit
