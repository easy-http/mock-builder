<?php

namespace EasyHttp\MockBuilder;

use EasyHttp\MockBuilder\Contracts\ResponseBuilder;
use EasyHttp\MockBuilder\ResponseBuilders\GuzzleResponseBuilder;

class Expectation
{
    protected ResponseBuilder $response;

    private string $method;

    public function then(): ResponseBuilder
    {
        $this->response = new GuzzleResponseBuilder();

        return $this->response;
    }

    public function getResponse(): ResponseBuilder
    {
        return $this->response;
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
