# Callable resolver

This package provides factory producing instances of [ellipse/dispatcher](https://github.com/ellipsephp/dispatcher) resolving callables as [Psr-15 middleware](https://github.com/http-interop/http-server-middleware) and [Psr-15 request handler](https://github.com/http-interop/http-server-handler).

**Require** php >= 7.1

**Installation** `composer require ellipse/dispatcher-callable`

**Run tests** `./vendor/bin/kahlan`

- [Getting started](https://github.com/ellipsephp/dispatcher-callable#getting-started)

## Getting started

This package provides an `Ellipse\Dispatcher\CallableResolver` class implementing `Ellipse\DispatcherFactoryInterface` which allows to decorate any other instance implementing this interface.

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
