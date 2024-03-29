<?php

namespace Framework\Http;

use Psr\Http\Message\ResponseInterface;

class ResponseSender
{
    static function send(ResponseInterface $response): void
    {
        header(sprintf(
            'HTTP/%s %d %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        ));
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("{$name}: {$value}");
            }
        }
        echo $response->getBody()->getContents();
    }
}
