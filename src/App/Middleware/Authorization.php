<?php

namespace App\Middleware;

use Middleware;
use Psr\Http\Message\ServerRequestInterface;

class Authorization implements Middleware
{
    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
    }
}
