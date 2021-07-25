<?php

namespace Framework\Http\Router\Route;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Result;

interface Route
{
    /**
     * Checks if request matches route
     *
     * @param ServerRequestInterface $request
     * @return Result|null - If matched - Result, otherwise - null
     */
    public function match(ServerRequestInterface $request): ?Result;

    /**
     * Generates uri
     *
     * @param string $name
     * @param array $arguments
     * @return string
     */
    public function generateUri(string $name, array $arguments = []): string;
}
