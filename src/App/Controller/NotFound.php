<?php

namespace App\Controller;

use Slim\Psr7\Response;
use Slim\Psr7\Factory\StreamFactory;
use Fig\Http\Message\StatusCodeInterface;

class NotFound implements Controller
{
    public function __invoke()
    {
        $message = "Page not found!";
        return (new Response(StatusCodeInterface::STATUS_NOT_FOUND))->withBody((new StreamFactory())->createStream($message));
    }
}
