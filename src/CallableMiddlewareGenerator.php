<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use IteratorAggregate;

use Ellipse\Middleware\CallableMiddleware;

class CallableMiddlewareGenerator implements IteratorAggregate
{
    /**
     * The iterable list of middleware which may be callable.
     *
     * @var iterable
     */
    private $middleware;

    /**
     * Set up a callable middleware with the given iterable list of middleware.
     *
     * @param iterable $middleware
     */
    public function __construct(iterable $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * This is a generator proxying the iterable list of middleware by wrapping
     * the callable ones inside a callable middleware.
     */
    public function getIterator()
    {
        foreach ($this->middleware as $middleware) {

            yield is_callable($middleware)
                ? new CallableMiddleware($middleware)
                : $middleware;

        }
    }
}
