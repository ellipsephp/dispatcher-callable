<?php declare(strict_types=1);

namespace Ellipse\Dispatcher\Exceptions;

use UnexpectedValueException;

class MiddlewareResponseTypeException extends UnexpectedValueException implements DispatcherExceptionInterface
{
    public function __construct($value)
    {
        $template = "A value of type %s was returned from a callable middleware - implementation of Psr\Http\Message\ResponseInterface expected";

        $msg = sprintf($template, is_object($value) ? get_class($value) : gettype($value));

        parent::__construct($msg);
    }
}
