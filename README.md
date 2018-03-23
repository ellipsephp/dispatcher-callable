# Callable resolver

This package provides a factory decorator for objects implementing `Ellipse\DispatcherFactoryInterface` from [ellipse/dispatcher](https://github.com/ellipsephp/dispatcher) package. It allows to produce instances of `Ellipse\Dispatcher` using callables as middleware and request handler.

**Require** php >= 7.0

**Installation** `composer require ellipse/dispatcher-callable`

**Run tests** `./vendor/bin/kahlan`

- [Create a dispatcher factory resolving callables](#create-a-dispatcher-factory-resolving-callables)

## Create a dispatcher factory resolving callables

This package provides an `Ellipse\Dispatcher\CallableResolver` class implementing `Ellipse\DispatcherFactoryInterface` which allows to decorate any other object implementing this interface.

Once decorated, the resulting dispatcher factory can be used to produce instances of `Ellipse\Dispatcher` by resolving callables as `Ellipse\Middleware\CallableMiddleware` from the [ellipse/middleware-callable](https://github.com/ellipsephp/middleware-callable) package or as `Ellipse\Handlers\CallableRequestHandler` from the [ellipse/handlers-callable](https://github.com/ellipsephp/handlers-callable) package.

`CallableMiddleware` and `CallableRequestHandler` logic is described on the [ellipse/middleware-callable](https://github.com/ellipsephp/middleware-callable#using-callables-as-middleware) and [ellipse/handlers-callable](https://github.com/ellipsephp/handlers-callable#using-callables-as-request-handlers) documentation pages.

```php
<?php

namespace App;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use Ellipse\DispatcherFactory;
use Ellipse\Dispatcher\CallableResolver;

// Decorate a DispatcherFactoryInterface implementation with a CallableResolver.
$factory = new CallableResolver(new DispatcherFactory);

// This callable acts as a middleware.
$middleware = function (ServerRequestInterface $request, RequestHandlerInterface $handler) {

    // ...

}

// This callable acts as a request handler.
$handler = function (ServerRequestInterface $request) {

    // ...

}

// A dispatcher using both callables and Psr-15 instances can now be created.
$dispatcher = $factory($handler, [$middleware, new SomeMiddleware]);
```
