<?php

use function Eloquent\Phony\Kahlan\stub;
use function Eloquent\Phony\Kahlan\mock;

use Psr\Http\Server\MiddlewareInterface;

use Ellipse\Dispatcher\CallableMiddleware;
use Ellipse\Dispatcher\CallableMiddlewareGenerator;

describe('CallableMiddlewareGenerator', function () {

    beforeEach(function () {

        $this->middleware1 = mock(MiddlewareInterface::class)->get();
        $this->middleware2 = stub();
        $this->middleware3 = mock(MiddlewareInterface::class)->get();

        $this->middleware = [
            $this->middleware1,
            $this->middleware2,
            $this->middleware3,
            'middleware4',
        ];

    });

    it('should be an instance of Traversable', function () {

        $test = new CallableMiddlewareGenerator(['middleware1', 'middleware2']);

        expect($test)->toBeAnInstanceOf(Traversable::class);

    });

    context('when transformed to an array via iterator_to_array()', function () {

        it('should wrap the callable into a CallableMiddleware', function () {

            $test = function ($middleware) {

                $generator = new CallableMiddlewareGenerator($middleware);

                $test = iterator_to_array($generator);

                expect($test[0])->toBe($this->middleware1);
                expect($test[1])->toEqual(new CallableMiddleware($this->middleware2));
                expect($test[2])->toBe($this->middleware3);
                expect($test[3])->toEqual('middleware4');

            };

            $test($this->middleware);
            $test(new ArrayIterator($this->middleware));
            $test(new class ($this->middleware) implements IteratorAggregate
            {
                public function __construct($middleware) { $this->middleware = $middleware; }
                public function getIterator() { return new ArrayIterator($this->middleware); }
            });

        });

        it('should not fail when used multiple time with iterator_to_array()', function () {

            $test = function ($middleware) {

                $generator = new CallableMiddlewareGenerator($middleware);

                $test = iterator_to_array($generator);

                expect($test[0])->toBe($this->middleware1);
                expect($test[1])->toEqual(new CallableMiddleware($this->middleware2));
                expect($test[2])->toBe($this->middleware3);
                expect($test[3])->toEqual('middleware4');

                $test = iterator_to_array($generator);

                expect($test[0])->toBe($this->middleware1);
                expect($test[1])->toEqual(new CallableMiddleware($this->middleware2));
                expect($test[2])->toBe($this->middleware3);
                expect($test[3])->toEqual('middleware4');

            };

            $test($this->middleware);
            $test(new ArrayIterator($this->middleware));
            $test(new class ($this->middleware) implements IteratorAggregate
            {
                public function __construct($middleware) { $this->middleware = $middleware; }
                public function getIterator() { return new ArrayIterator($this->middleware); }
            });

        });

    });

});
