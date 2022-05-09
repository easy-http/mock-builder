<?php

namespace EasyHttp\MockBuilder\ResponseBuilders;

use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class GuzzleResponseBuilder implements ResponseBuilder
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $body = '';
    private $bodyHandler;
    private array $json = [];
    private ?RequestInterface $request;

    public function setRequest(?RequestInterface $request = null)
    {
        $this->request = $request;
    }

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

    public function body($body): self
    {
        $this->json = [];
        $this->body = '';
        $this->bodyHandler = null;

        if (is_callable($body)) {
            $this->bodyHandler = $body;
        }

        if (is_string($body)) {
            $this->body = $body;
        }

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
        $body = '';

        if ($this->bodyHandler) {
            $clousure = $this->bodyHandler;
            $body = $clousure($this->request);
        } elseif (!empty($this->json)) {
            $body = json_encode($this->json, true);
        } elseif (!empty($this->body)) {
            $body = $this->body;
        }

        return new FulfilledPromise(
            new Response($this->statusCode, $this->headers, $body)
        );
    }
}
