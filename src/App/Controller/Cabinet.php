<?php

namespace App\Controller;

use App\Middleware\Authorization;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\StreamFactory;

class Cabinet implements Controller
{
    public function main(ServerRequestInterface $request)
    {
        $userName = $request->getAttribute(Authorization::USERNAME_ATTR);
        $message = "Logged as {$userName}";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }
}
