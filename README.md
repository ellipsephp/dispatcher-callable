# Callable resolver

This package provides a factory decorator for objects implementing `Ellipse\DispatcherFactoryInterface` from [ellipse/dispatcher](https://github.com/ellipsephp/dispatcher) package.

The resulting factory can produce instances of `Ellipse\Dispatcher` using callables as [Psr-15](https://www.php-fig.org/psr/psr-15/) middleware and request handler.

**Require** php >= 7.0

**Installation** `composer require ellipse/dispatcher-callable`

**Run tests** `./vendor/bin/kahlan`

- [Create a dispatcher using callables](https://github.com/ellipsephp/dispatcher-callable#create-a-dispatcher-using-callables)

## Create a dispatcher using callables

This package provides an `Ellipse\Dispatcher\CallableResolver` class implementing `Ellipse\DispatcherFactoryInterface` which allows to decorate any other object implementing this interface.

Once decorated, the resulting dispatcher factory can be used to produce instances of `Ellipse\Dispatcher` using callables as Psr-15 middleware and request handler.

```php
<?php

namespace App;

use Ellipse\DispatcherFactory;
use Ellipse\Dispatcher\CallableResolver;

// Get some incoming Psr-7 request.
$request = some_psr7_request_factory();

// Get a decorated dispatcher factory.
$factory = new CallableResolver(new DispatcherFactory);

// A dispatcher using both callables and Psr-15 instances can now be created.
$middleware = function ($request, $handler) {

    // This callable behave like a Psr-15 middleware.

};

$handler = function ($request) {

    // This callable behave like a Psr-15 request handler.

};

$dispatcher = $factory($handler, [$middleware, new SomeMiddleware]);

// This works :-)
$dispatcher->handle($request);
```
