<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\StreamFactory;

class Cabinet implements Controller
{
    public function main(ServerRequestInterface $request)
    {
        $message = "Logged as {$request->getAttribute('username')}";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }
}
