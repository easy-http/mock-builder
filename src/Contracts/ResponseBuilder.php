<?php

namespace EasyHttp\MockBuilder\Contracts;

use Psr\Http\Message\RequestInterface;

interface ResponseBuilder
{
    public function statusCode(int $statusCode): self;
    public function headers(array $headers): self;
    public function body($body): self;
    public function json(array $json): self;
    public function setRequest(?RequestInterface $request = null);
}
