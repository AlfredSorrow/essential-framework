<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Profiler implements Middleware
{
    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {

        $start = microtime(true);
        $response = $next($request);
        $end = microtime(true);

        return $response->withHeader('X-Profiler', $end - $start);
    }
}
