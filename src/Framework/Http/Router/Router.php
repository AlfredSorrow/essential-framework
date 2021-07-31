<?php

namespace Framework\Http\Router;

use Framework\Http\Router\Result;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Exception\RequestNotMatchedException;
use Framework\Http\Router\Exception\RouteNotFoundException;

interface Router
{
    /**
     * Finds matched route for request
     *
     * @param ServerRequestInterface $request
     * @return Result
     * 
     * @throws RequestNotMatchedException
     */
    public function match(ServerRequestInterface $request): Result;

    /**
     * Generating uri
     *
     * @param string $name
     * @param array $params
     * @return string
     * 
     * @throws RouteNotFoundException
     */
    public function generateUri(string $name, array $params = []): string;
}
