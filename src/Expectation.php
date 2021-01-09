<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use EasyHttp\MockBuilder\ResponseBuilders\GuzzleResponseBuilder;

class Expectation
{
    protected ResponseBuilder $responseBuilder;

    private string $method;

    public function then(): ResponseBuilder
    {
        $this->responseBuilder = new GuzzleResponseBuilder();

        return $this->responseBuilder;
    }

    public function responseBuilder(): ResponseBuilder
    {
        return $this->responseBuilder;
    }

    public function getMethod(): ?string
    {
        return $this->method ?? null;
    }

    public function methodIs(string $method): self
    {
        $this->method = $method;

        return $this;
    }
}
