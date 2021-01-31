<?php

namespace EasyHttp\MockBuilder\Contracts;

interface ResponseBuilder
{
    public function statusCode(int $statusCode): self;
    public function headers(array $headers): self;
    public function body(string $body): self;
    public function json(array $json): self;
}
