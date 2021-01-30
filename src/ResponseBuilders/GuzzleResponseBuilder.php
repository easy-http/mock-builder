<?php

namespace EasyHttp\MockBuilder\ResponseBuilders;

use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;

class GuzzleResponseBuilder implements ResponseBuilder
{
    private int $statusCode = 200;
    private array $headers = [];
    private ?string $body;

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function body(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function response(): FulfilledPromise
    {
        return new FulfilledPromise(
            new Response($this->statusCode, $this->headers, $this->getBody())
        );
    }
}
