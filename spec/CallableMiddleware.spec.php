<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\CallableMiddleware;
use Ellipse\Dispatcher\Exceptions\ResponseTypeException;

describe('CallableMiddleware', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->middleware = new CallableMiddleware($this->callable);

    });

    it('should implement MiddlewareInterface', function () {

        expect($this->middleware)->toBeAnInstanceOf(MiddlewareInterface::class);

    });

    describe('->process()', function () {

        context('when the callable returns an implementation of ResponseInterface', function () {

            it('should proxy the callable', function () {

                $request = mock(ServerRequestInterface::class)->get();
                $response = mock(ResponseInterface::class)->get();

                $handler = mock(RequestHandlerInterface::class)->get();

                $this->callable->with($request, $handler)->returns($response);

                $test = $this->middleware->process($request, $handler);

                expect($test)->toBe($response);

            });

        });

        context('when the callable does not return an implementation of ResponseInterface', function () {

            it('should throw a ResponseTypeException', function () {

                $request = mock(ServerRequestInterface::class)->get();

                $handler = mock(RequestHandlerInterface::class)->get();

                $this->callable->with($request, $handler)->returns('response');

                $test = function () use ($request, $handler) {

                    $this->middleware->process($request, $handler);

                };

                $exception = new ResponseTypeException('response');

                expect($test)->toThrow($exception);

            });

        });

    });

});
