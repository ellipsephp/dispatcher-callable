<?php

use Ellipse\Dispatcher\Exceptions\DispatcherExceptionInterface;
use Ellipse\Dispatcher\Exceptions\RequestHandlerResponseTypeException;

describe('RequestHandlerResponseTypeException', function () {

    it('should implement DispatcherExceptionInterface', function () {

        $test = new RequestHandlerResponseTypeException('invalid');

        expect($test)->toBeAnInstanceOf(DispatcherExceptionInterface::class);

    });

});
