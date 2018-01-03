<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

use Interop\Http\Server\RequestHandlerInterface;

use Ellipse\Dispatcher\CallableRequestHandler;
use Ellipse\Dispatcher\Exceptions\RequestHandlerResponseTypeException;

describe('CallableRequestHandler', function () {

    beforeEach(function () {

        $this->callable = stub();

        $this->handler = new CallableRequestHandler($this->callable);

    });

    it('should implement RequestHandlerInterface', function () {

        expect($this->handler)->toBeAnInstanceOf(RequestHandlerInterface::class);

    });

    describe('->process()', function () {

        context('when the callable returns an implementation of ResponseInterface', function () {

            it('should proxy the callable', function () {

                $request = mock(ServerRequestInterface::class)->get();
                $response = mock(ResponseInterface::class)->get();

                $this->callable->with($request)->returns($response);

                $test = $this->handler->handle($request);

                expect($test)->toBe($response);

            });

        });

        context('when the callable does not return an implementation of ResponseInterface', function () {

            it('should throw a RequestHandlerResponseTypeException', function () {

                $request = mock(ServerRequestInterface::class)->get();
                $response = mock(ResponseInterface::class)->get();

                $this->callable->with($request)->returns('response');

                $test = function () use ($request) {

                    $this->handler->handle($request);

                };

                $exception = new RequestHandlerResponseTypeException('response');

                expect($test)->toThrow($exception);

            });

        });

    });

});
