<?php

namespace EasyHttp\MockBuilder\Contracts;

interface ResponseBuilder
{
    public function body(string $body): self;
}