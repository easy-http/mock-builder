<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use EasyHttp\MockBuilder\ResponseBuilders\GuzzleResponseBuilder;

class Expectation
{
    protected ResponseBuilder $responseBuilder;

    private string $method;
    private array $queryParams = [];

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

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function methodIs(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function queryParamIs(string $key, string $value): self
    {
        $this->queryParams[$key] = $value;

        return $this;
    }
}
