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
                if ($request->getMethod() === $method) {
                    $matches = true;
                }
            }

            parse_str($request->getUri()->getQuery(), $params);

            foreach ($expectation->getQueryParams() as $param => $value) {
                if (!array_key_exists($param, $params)) {
                    $matches = false;
                    break;
                }

                $matches = ($params[$param] === $value);

                if (!$matches) {
                    break;
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
