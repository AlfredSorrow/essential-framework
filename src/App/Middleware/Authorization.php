<?php

namespace App\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class Authorization implements Middleware
{
    private array $users;

    public const USERNAME_ATTR = '_username';

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(ServerRequestInterface $request, callable $next): ResponseInterface
    {
        $user = $request->getServerParams()['PHP_AUTH_USER'] ?? null;
        $pass = $request->getServerParams()['PHP_AUTH_PW'] ?? null;

        if ($user && $pass) {
            foreach ($this->users as $userName => $password) {
                if ($user === $userName && $pass === $password) {
                    return $next($request->withAttribute(self::USERNAME_ATTR, $userName));
                }
            }
        }

        return (new Response(401))->withHeader('WWW-Authenticate', 'Basic realm=restricted area');
    }
}
