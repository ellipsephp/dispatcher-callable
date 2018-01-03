<?php declare(strict_types=1);

namespace Ellipse\Dispatcher;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\Exceptions\ResponseTypeException;

class CallableRequestHandler implements RequestHandlerInterface
{
    /**
     * The callable to treat as a request handler.
     *
     * @var callable
     */
    private $callable;

    /**
     * Set up a callable request handler with the given callable.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * Proxy the callable.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Ellipse\Dispatcher\Exceptions\ResponseTypeException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $response = ($this->callable)($request);

        if ($response instanceof ResponseInterface) {

            return $response;

        }

        throw new ResponseTypeException($response);
    }
}
