<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Ellipse\Dispatcher;
use Ellipse\DispatcherFactoryInterface;
use Ellipse\Dispatcher\CallableResolver;
use Ellipse\Dispatcher\CallableRequestHandler;
use Ellipse\Dispatcher\CallableMiddlewareGenerator;

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

        context('when no iterable list of middleware is given', function () {

            it('should proxy the delegate with an empty array wrapped into a callable middleware generator', function () {

                $generator = new CallableMiddlewareGenerator([]);

                $this->delegate->__invoke->with('~', $generator)->returns($this->dispatcher);

                $test = ($this->factory)('handler');

                expect($test)->toBe($this->dispatcher);

            });

        });

        context('when an iterable list of middleware is given', function () {

            it('should proxy the delegate with the given itetable list of middleware wrapped into a callable middleware generator', function () {

                $test = function ($middleware) {

                    $generator = new CallableMiddlewareGenerator($middleware);

                    $this->delegate->__invoke->with('~', $generator)->returns($this->dispatcher);

                    $test = ($this->factory)('handler', $middleware);

                    expect($test)->toBe($this->dispatcher);

                };

                $middleware = ['middleware1', 'middleware2'];

                $test($middleware);
                $test(new ArrayIterator($middleware));
                $test(new class ($middleware) implements IteratorAggregate
                {
                    public function __construct($middleware) { $this->middleware = $middleware; }
                    public function getIterator() { return new ArrayIterator($this->middleware); }
                });

            });

        });

    });

});
