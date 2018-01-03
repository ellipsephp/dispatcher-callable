<?php

use Ellipse\Dispatcher\Exceptions\DispatcherExceptionInterface;
use Ellipse\Dispatcher\Exceptions\MiddlewareResponseTypeException;

describe('MiddlewareResponseTypeException', function () {

    it('should implement DispatcherExceptionInterface', function () {

        $test = new MiddlewareResponseTypeException('invalid');

        expect($test)->toBeAnInstanceOf(DispatcherExceptionInterface::class);

    });

});
