<?php

namespace Framework\Http\Router\Route;

use Psr\Http\Message\ServerRequestInterface;
use Framework\Http\Router\Result;

class RegexpRoute implements Route
{
    private string $method;
    private string $name;
    private string $pattern;
    private array $handlers;
    private array $tokens;

    /**
     * RegexpRoute
     *
     * @param string $method
     * @param string $name
     * @param string $pattern
     * @param array $handler
     * @param array $tokens
     */
    public function __construct(string $method, string $name, string $pattern, array $handlers, array $tokens = [])
    {
        $this->method = $method;
        $this->name = $name;
        $this->pattern = $pattern;
        $this->handler = $handlers;
        $this->tokens = $tokens;
    }

    /**
     * @inheritDoc
     */
    public function match(ServerRequestInterface $request): ?Result
    {

        if ($this->method !== $request->getMethod()) {
            return null;
        }

        $pattern = preg_replace_callback('~:([^\/]+)~', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^\/]+';
            return "(?P<{$argument}>{$replace})";
        }, $this->pattern);


        if (preg_match('~^' . $pattern . '$~i', $request->getUri()->getPath(), $matches)) {
            return new Result(
                $this->name,
                $this->handler,
                array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)
            );
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function generateUri(string $name, array $arguments = []): string
    {
        if ($this->name !== $name) {
            return '';
        }

        $url = preg_replace_callback('~:([^\/]+)~', function ($matches) use ($arguments) {
            $argument = $matches[1];
            if (!array_key_exists($argument, $arguments)) {
                throw new \InvalidArgumentException();
            }

            return $arguments[$argument];
        }, $this->pattern);

        return $url;
    }
}
