<?php

namespace Framework\Http\Router\Exception;

use Psr\Http\Message\ServerRequestInterface;

class RequestNotMatchedException extends \LogicException
{
    public function __construct(ServerRequestInterface $request)
    {
        $message = "NO ROUTE FOUND FOR REQUEST: {$request->getMethod()} {$request->getUri()}";
        parent::__construct($message);
    }
}
