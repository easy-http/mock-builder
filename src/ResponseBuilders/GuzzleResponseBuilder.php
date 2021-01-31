<?php

namespace EasyHttp\MockBuilder\ResponseBuilders;

use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;

class GuzzleResponseBuilder implements ResponseBuilder
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';
    private array $json = [];

    public function statusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function headers(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function body(string $body): self
    {
        $this->body = $body;
        $this->json = [];

        return $this;
    }

    public function getJson(): array
    {
        return $this->json;
    }

    public function json(array $json): self
    {
        $this->body = '';
        $this->json = $json;

        return $this;
    }

    public function response(): FulfilledPromise
    {
        $body = $this->json ? json_encode($this->json, true) : $this->body;

        return new FulfilledPromise(
            new Response($this->statusCode, $this->headers, $body)
        );
    }
}
