<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Ellipse\Dispatcher;
use Ellipse\DispatcherFactoryInterface;

class CallableResolver implements DispatcherFactoryInterface
{
    /**
     * The delegate.
     *
     * @var \Ellipse\DispatcherFactoryInterface
     */
    private $delegate;

    /**
     * Set up a callable resolver with the given delegate.
     *
     * @param \Ellipse\DispatcherFactoryInterface $delegate
     */
    public function __construct(DispatcherFactoryInterface $delegate)
    {
        $this->delegate = $delegate;
    }

    /**
     * Proxy the delegate by wrapping callable request handler and iterable list
     * of middleware into callable resolvers.
     *
     * @param mixed     $handler
     * @param iterable  $middleware
     * @return \Ellipse\Dispatcher
     */
    public function __invoke($handler, iterable $middleware = []): Dispatcher
    {
        $middleware = new CallableMiddlewareGenerator($middleware);

        if (is_callable($handler)) {

            $handler = new CallableRequestHandler($handler);

        }

        return ($this->delegate)($handler, $middleware);
    }
}
