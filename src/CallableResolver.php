<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Ellipse\Dispatcher;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Middleware\CallableMiddleware;

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
     * Proxy the delegate by wrapping callable request handler and callable
     * middleware around the given request handler and middleware queue.
     *
     * @param mixed $handler
     * @param array $middleware
     * @return \Ellipse\Dispatcher
     */
    public function __invoke($handler, array $middleware = []): Dispatcher
    {
        if (is_callable($handler)) {

            $handler = new CallableRequestHandler($handler);

        }

        return ($this->delegate)($handler, array_map(function ($middleware) {

            return is_callable($middleware)
                ? new CallableMiddleware($middleware)
                : $middleware;

        }, $middleware));
    }
}
