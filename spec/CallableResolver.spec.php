<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Ellipse\Dispatcher;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Dispatcher\CallableResolver;
use Ellipse\Middleware\CallableMiddleware;
use Ellipse\Handlers\CallableRequestHandler;

describe('CallableResolver', function () {

    beforeEach(function () {

        $this->delegate = mock(DispatcherFactoryInterface::class);

        $this->factory = new CallableResolver($this->delegate->get());

    });

    it('should implement DispatcherFactoryInterface', function () {

        expect($this->factory)->toBeAnInstanceOf(DispatcherFactoryInterface::class);

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->dispatcher = mock(Dispatcher::class)->get();

        });

        context('when the given request handler is not a callable', function () {

            it('should proxy the delegate with the given request handler', function () {

                $this->delegate->__invoke->with('handler', '~')->returns($this->dispatcher);

                $test = ($this->factory)('handler', []);

                expect($test)->toBe($this->dispatcher);

            });

        });

        context('when the given request handler is a callable', function () {

            it('should proxy the delegate with the given request handler wrapped into a CallableRequestHandler', function () {

                $callable = stub();

                $handler = new CallableRequestHandler($callable);

                $this->delegate->__invoke->with($handler, '~')->returns($this->dispatcher);

                $test = ($this->factory)($callable, []);

                expect($test)->toBe($this->dispatcher);

            });

        });

        context('when no middleware queue is given', function () {

            it('should proxy the delegate with an empty array', function () {

                $this->delegate->__invoke->with('~', [])->returns($this->dispatcher);

                $test = ($this->factory)('handler');

                expect($test)->toBe($this->dispatcher);

            });

        });

        context('when a middleware queue is given', function () {

            it('should proxy the delegate with CallableMiddleware wrapped around the callable values of the middleware queue', function () {

                $callable = stub();

                $this->delegate->__invoke
                    ->with('~', ['middleware', new CallableMiddleware($callable)])
                    ->returns($this->dispatcher);

                $test = ($this->factory)('handler', ['middleware', $callable]);

                expect($test)->toBe($this->dispatcher);

            });

        });

    });

});
