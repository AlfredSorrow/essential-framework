<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\StreamFactory;

class About implements Controller
{
    public function __invoke(ServerRequestInterface $request)
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        $message = "Hello, {$name}!";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }
}
