<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\StreamFactory;

class Index implements Controller
{
    public function main(ServerRequestInterface $request)
    {
        $name = $request->getQueryParams()['name'] ?? 'Guest';
        $message = "Hello, {$name}!";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }
}
