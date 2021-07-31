<?php

namespace App\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Psr7\Factory\StreamFactory;

class Post implements Controller
{
    public function list(ServerRequestInterface $request)
    {
        $message = "There will be posts";
        return (new Response())->withBody((new StreamFactory())->createStream($message));
    }

    public function show(ServerRequestInterface $request)
    {
        $postId = $request->getAttribute('id');
        return (new Response())->withBody((new StreamFactory())->createStream($postId));
    }
}
