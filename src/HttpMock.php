<?php

namespace EasyHttp\MockBuilder;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\RequestInterface;

class HttpMock
{
    private MockBuilder $builder;

    public function __construct(MockBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function __invoke(RequestInterface $request): FulfilledPromise
    {
        foreach ($this->builder->getExpectations() as $expectation)
        {
            $matches = false;

            if ($method = $expectation->getMethod()) {
                if ($request->getMethod() !== $method) {
                    $matches = true;
                }
            }

            if ($matches) {
                return $expectation->responseBuilder()->response();
            }
        }

        return $this->fallbackResponse();
    }

    private function fallbackResponse(): FulfilledPromise
    {
        return new FulfilledPromise(
            new Response(404)
        );
    }
}